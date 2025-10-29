<?php

namespace App\Http\Controllers;

use App\Events\TaxPaymentSucceeded;
use App\Mail\FormsSubmittedWithAttachments;
use App\Models\TaxReturn;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class StripePaymentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse
     */
    public function payment(Request $request, $id)
    {
        $tax = TaxReturn::query()->where('id', '=', $id)
            ->where('user_id', '=', Auth::id())
            ->first();



        if(!$tax) {
           return redirect()->route('home')->with('error', 'Tax not found!');
        }

        if($tax->payment_status == 'paid') {
            return redirect()->route('home')->with('error', 'You already paid this tax!');
        }


        if($tax->form_status == 'complete') {
            return redirect()->route('home')->with('error', 'You form already completed!');
        }

        $amount = env('AMOUNT', 100);
        return view('pages.payment', compact('tax', 'amount'));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function makePayment(Request $request)
    {
        $validated = $request->validate([
            'stripeToken' => 'required|string',
            'tax_id' => 'required|exists:tax_returns,id',
            'agree' => 'required|in:1'
        ]);

        $tax = TaxReturn::where('id', $validated['tax_id'])
            ->where('user_id', Auth::id())
            ->first();

        if (!$tax) {
            return redirect()->route('home')->with('error', 'Tax not found!');
        }

        if ($tax->payment_status === 'paid') {
            return redirect()->route('home')->with('error', 'This tax return is already paid.');
        }

        if($tax->form_status == 'complete') {
            return redirect()->route('home')->with('error', 'You form already completed!');
        }


        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        DB::beginTransaction();

        try {
            $charge = \Stripe\Charge::create([
                'amount' => intval(env('AMOUNT', 100) * 100),
                'currency' => 'aud', // ✅ use your account’s currency
                'source' => $validated['stripeToken'],
                'description' => 'Tax Payment for Tax ID #' . $tax->id,
                'metadata' => [
                    'user_id' => Auth::id(),
                    'tax_id' => $tax->id,
                    'email' => Auth::user()->email ?? 'N/A'
                ]
            ]);

            $tax->update([
                'payment_status' => 'paid',
                'form_status' => 'complete',
                'payment_reference' => $charge->id ?? null
            ]);

            Transaction::create([
                'user_id' => Auth::id(),
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
            return redirect()->route('home')
                ->with('success',
                    'Thank you for your payment.<br>
Thanks for choosing TaxEasy to complete your tax return.<br>
Our team will reach you out shortly if we have any questions.<br>
You should expect your return around 14 days from confirmed submission to the Australian Taxation Office.');

        } catch (\Stripe\Exception\CardException $e) {
            DB::rollBack();
            $tax->update(['form_status' => 'incomplete']);
            return back()->with('error', $e->getError()->message);
        } catch (\Exception $e) {
            DB::rollBack();
            $tax->update(['form_status' => 'incomplete']);
            return back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

}
