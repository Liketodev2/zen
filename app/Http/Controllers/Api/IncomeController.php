<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\IncomeResource;
use App\Models\Forms\Income;
use App\Models\TaxReturn;
use App\Services\IncomeFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class IncomeController extends Controller
{

    /**
     * @var IncomeFileService
     */
    protected $fileService;


    /**
     * @param IncomeFileService $fileService
     */
    public function __construct(IncomeFileService $fileService)
    {
        $this->fileService = $fileService;
    }


    /**
     * @param Request $request
     * @return IncomeResource|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function income(Request $request)
    {
        $arrayFields = [
            'salary', 'interests', 'dividends', 'government_allowances',
            'government_pensions', 'partnerships', 'annuities',
            'superannuation', 'super_lump_sums', 'ess', 'personal_services',
            'business_income', 'business_losses', 'foreign_income', 'other_income'
        ];

        foreach ($arrayFields as $key) {
            if ($request->has($key) && is_string($request->$key)) {
                $decoded = json_decode($request->$key, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $request->merge([$key => $decoded]);
                }
            }
        }

        $rules = ['tax_id' => 'required|exists:tax_returns,id'];
        foreach ($arrayFields as $field) {
            $rules[$field] = 'nullable|array';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'validation' => true], 422);
        }

        $validated = $validator->validated();

        $taxReturn = TaxReturn::where('id', $validated['tax_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if(!$taxReturn) {
            return response()->json(['error' => 'Tax return not found!', 'validation' => false], 404);
        }


        $income = Income::firstOrCreate(['tax_return_id' => $taxReturn->id]);

        $data = $income->toArray();
        foreach ($arrayFields as $field) {
            $data[$field] = $validated[$field] ?? ($data[$field] ?? null);
        }

        $income->update($data);

        return (new IncomeResource($income->fresh()))
            ->additional([
                'success' => true,
                'message' => 'Income data saved successfully!',
            ]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveCapitalGains(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tax_id' => 'required|exists:tax_returns,id',
            'capital_gains' => 'nullable|array',
            'capital_gains.cgt_attachment' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'validation' => true], 422);
        }

        $taxReturn = TaxReturn::where('id', $request->tax_id)
            ->where('user_id', $request->user()->id)
            ->first();

        if(!$taxReturn) {
            return response()->json(['error' => 'Tax return not found!', 'validation' => false], 404);
        }

        $income = Income::firstOrCreate(['tax_return_id' => $taxReturn->id]);

        $attach = $income->attach ?? [];
        $data = $income->toArray();

        // Merge existing capital gains data with request data
        $existingCapitalGains = $data['capital_gains'] ?? [];
        $requestCapitalGains = $request->input('capital_gains', []);
        $data['capital_gains'] = array_merge($existingCapitalGains, $requestCapitalGains);

        $this->fileService->handleCapitalGainsFiles($request, $attach, $data);

        $income->update([
            'capital_gains' => $data['capital_gains'],
            'attach' => $attach
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Capital gains saved successfully!',
            'data' => new IncomeResource($income->fresh()),
        ]);
    }



    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveManagedFunds(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tax_id' => 'required|exists:tax_returns,id',
            'managed_funds' => 'nullable|array',
            'managed_fund_files.*' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'validation' => true], 422);
        }

        $taxReturn = TaxReturn::where('id', $request->tax_id)
            ->where('user_id', $request->user()->id)
            ->first();

        if(!$taxReturn) {
            return response()->json(['error' => 'Tax return not found!', 'validation' => false], 404);
        }


        $income = Income::firstOrCreate(['tax_return_id' => $taxReturn->id]);
        $attach = $income->attach ?? [];
        $data = $income->toArray();

//        $data['managed_funds'] = $request->input('managed_funds', $data['managed_funds'] ?? []);

        // Merge existing managed funds data with request data
        $existingManagedFunds = $data['managed_funds'] ?? [];
        $requestManagedFunds = $request->input('managed_funds', []);
        $data['managed_funds'] = array_merge($existingManagedFunds, $requestManagedFunds);

        $this->fileService->handleManagedFundsFiles($request, $attach, $data);
        $income->update([
            'managed_funds' => $data['managed_funds'],
            'attach' => $attach
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Managed funds saved successfully!',
            'data' => new IncomeResource($income->fresh()),
        ]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
//    public function saveTerminationPayments(Request $request)
//    {
//        $validator = Validator::make($request->all(), [
//            'tax_id' => 'required|exists:tax_returns,id',
//            'termination_payments' => 'nullable|array',
//            'termination_payments.*.etp_files.*' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
//        ]);
//
//        if ($validator->fails()) {
//            return response()->json([
//                'errors' => $validator->errors(),
//                'validation' => true,
//            ], 422);
//        }
//
//        $taxReturn = TaxReturn::where('id', $request->tax_id)
//            ->where('user_id', $request->user()->id)
//            ->first();
//
//        if (!$taxReturn) {
//            return response()->json([
//                'error' => 'Tax return not found!',
//                'validation' => false,
//            ], 404);
//        }
//
//        $income = Income::firstOrCreate(['tax_return_id' => $taxReturn->id]);
//
//        // Decode attachments safely
//        $attach = is_string($income->attach)
//            ? json_decode($income->attach, true) ?: []
//            : ($income->attach ?? []);
//
//        // ✅ Replace all termination payments instead of merging
//        $data['termination_payments'] = $request->input('termination_payments', []);
//
//        // Handle file uploads like saveRent
//        $this->fileService->handleTerminationPaymentsFilesForApi($request, $attach, $data);
//
//        // Update database
//        $income->update([
//            'termination_payments' => $data['termination_payments'],
//            'attach' => $attach,
//        ]);
//
//        return response()->json([
//            'success' => true,
//            'message' => 'Termination payments saved successfully!',
//            'data' => new IncomeResource($income->fresh()),
//        ]);
//    }


    public function saveTerminationPayments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tax_id' => 'required|exists:tax_returns,id',
            'termination_payments' => 'nullable|array',
            'termination_payments.*.etp_files.*' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'validation' => true,
            ], 422);
        }

        $taxReturn = TaxReturn::where('id', $request->tax_id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$taxReturn) {
            return response()->json([
                'error' => 'Tax return not found!',
                'validation' => false,
            ], 404);
        }

        $income = Income::firstOrCreate(['tax_return_id' => $taxReturn->id]);

        // Decode attachments safely
        $attach = is_string($income->attach)
            ? json_decode($income->attach, true) ?: []
            : ($income->attach ?? []);

        $newTerminationPayments = $request->input('termination_payments', []);

        // Delete all old files from S3
        if (!empty($attach['termination_payments'])) {
            foreach ($attach['termination_payments'] as $oldItem) {
                if (!empty($oldItem['etp_files'])) {
                    foreach ($oldItem['etp_files'] as $oldFile) {
                        Storage::disk('s3')->delete($oldFile);
                    }
                }
            }
        }

        // Reset attach termination payments
        $attach['termination_payments'] = [];

        // Assign new data
        $data['termination_payments'] = $newTerminationPayments;

        // Handle new file uploads
        $this->fileService->handleTerminationPaymentsFilesForApi($request, $attach, $data);

        $income->update([
            'termination_payments' => $data['termination_payments'],
            'attach' => $attach,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Termination payments saved successfully!',
            'data' => new IncomeResource($income->fresh()),
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveRent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tax_id' => 'required|exists:tax_returns,id',
            'rent' => 'nullable|array',
            'rent.*.rent_files.*' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'validation' => true,
            ], 422);
        }

        $taxReturn = TaxReturn::where('id', $request->tax_id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$taxReturn) {
            return response()->json([
                'error' => 'Tax return not found!',
                'validation' => false,
            ], 404);
        }

        $income = Income::firstOrCreate(['tax_return_id' => $taxReturn->id]);

        // Decode attachments safely
        $attach = is_string($income->attach)
            ? json_decode($income->attach, true) ?: []
            : ($income->attach ?? []);

        // ✅ Replace all rent data instead of merging
        $data['rent'] = $request->input('rent', []);

        // Handle file uploads
        $this->fileService->handleRentFilesForApi($request, $attach, $data);

        // Update database
        $income->update([
            'rent' => $data['rent'],
            'attach' => $attach,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Rent details saved successfully!',
            'data' => new IncomeResource($income->fresh()),
        ]);
    }


}
