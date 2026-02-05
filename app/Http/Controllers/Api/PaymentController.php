<?php

namespace App\Http\Controllers\Api;

use App\Events\TaxPaymentSucceeded;
use App\Http\Controllers\Controller;
use App\Models\TaxReturn;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Get payment information for a tax return
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function payment(Request $request, $id)
    {
        $tax = TaxReturn::query()
            ->where('id', '=', $id)
            ->where('user_id', '=', $request->user()->id)
            ->first();

        if (!$tax) {
            return response()->json([
                'success' => false,
                'message' => 'Tax return not found!'
            ], 404);
        }

        if ($tax->payment_status == 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'This tax return is already paid.'
            ], 400);
        }

        if ($tax->form_status == 'complete') {
            return response()->json([
                'success' => false,
                'message' => 'This form is already completed!'
            ], 400);
        }

        $amount = \App\Models\Plan::first()->price ?? env('AMOUNT', 100);

        return response()->json([
            'success' => true,
            'data' => [
                'tax_return' => [
                    'id' => $tax->id,
                    'payment_status' => $tax->payment_status,
                    'form_status' => $tax->form_status,
                ],
                'amount' => $amount,
                'currency' => 'AUD',
            ]
        ]);
    }

    /**
     * Process payment for a tax return
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function makePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'stripeToken' => 'required|string',
            'tax_id' => 'required|exists:tax_returns,id',
            'agree' => 'required|in:1,true'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'validation' => true], 422);
        }

        $validated = $validator->validated();

        $tax = TaxReturn::where('id', $validated['tax_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$tax) {
            return response()->json([
                'success' => false,
                'message' => 'Tax return not found!'
            ], 404);
        }

        if ($tax->payment_status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'This tax return is already paid.'
            ], 400);
        }

        if ($tax->form_status == 'complete') {
            return response()->json([
                'success' => false,
                'message' => 'This form is already completed!'
            ], 400);
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        DB::beginTransaction();

        try {
            $charge = \Stripe\Charge::create([
                'amount' => intval((\App\Models\Plan::first()->price ?? env('AMOUNT', 100)) * 100),
                'currency' => 'aud',
                'source' => $validated['stripeToken'],
                'description' => 'Tax Payment for Tax ID #' . $tax->id,
                'metadata' => [
                    'user_id' => $request->user()->id,
                    'tax_id' => $tax->id,
                    'email' => $request->user()->email ?? 'N/A'
                ]
            ]);

            $tax->update([
                'payment_status' => 'paid',
                'form_status' => 'complete',
                'payment_reference' => $charge->id ?? null
            ]);

            Transaction::create([
                'user_id' => $request->user()->id,
                'tax_return_id' => $tax->id,
                'stripe_charge_id' => $charge->id ?? null,
                'amount' => $charge->amount / 100,
                'currency' => $charge->currency,
                'status' => $charge->status === 'succeeded' ? 'paid' : 'failed',
                'description' => $charge->description,
                'metadata' => $charge->metadata,
            ]);

            DB::commit();

            // Dispatch event to send email
            TaxPaymentSucceeded::dispatch($tax);

            return response()->json([
                'success' => true,
                'message' => 'Payment successful! Our team will reach out if we have any questions. You should expect your return around 14 days from confirmed submission to the Australian Taxation Office.',
                'data' => [
                    'transaction_id' => $charge->id,
                    'amount' => $charge->amount / 100,
                    'currency' => $charge->currency,
                    'status' => $charge->status
                ]
            ]);

        } catch (\Stripe\Exception\CardException $e) {
            DB::rollBack();
            $tax->update(['form_status' => 'incomplete']);

            return response()->json([
                'success' => false,
                'message' => $e->getError()->message
            ], 400);

        } catch (\Exception $e) {
            DB::rollBack();
            $tax->update(['form_status' => 'incomplete']);

            return response()->json([
                'success' => false,
                'message' => 'Payment failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
