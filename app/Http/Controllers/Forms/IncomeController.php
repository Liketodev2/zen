<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use App\Services\IncomeFileService;
use Illuminate\Http\Request;
use App\Models\TaxReturn;
use App\Models\Forms\Income;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class IncomeController extends Controller
{

    /**
     * @var IncomeFileService
     */
    protected IncomeFileService $fileService;


    /**
     * @param IncomeFileService $fileService
     */
    public function __construct(IncomeFileService $fileService)
    {
        $this->fileService = $fileService;
    }


    /**
     * @param Request $request
     * @param $taxId
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    private function saveIncome(Request $request, $taxId, $id = null)
    {
        $taxReturn = TaxReturn::where('user_id', auth()->id())
            ->where('id', $taxId)
            ->first();

        if (!$taxReturn) {
            return response()->json([
                'success' => false,
                'message' => 'Tax Return not found'
            ], 404);
        }

        $rules = [
            'salary' => 'nullable|array',
            'interests' => 'nullable|array',
            'dividends' => 'nullable|array',
            'government_allowances' => 'nullable|array',
            'government_pensions' => 'nullable|array',
            'capital_gains' => 'nullable|array',
            'managed_funds' => 'nullable|array',
            'termination_payments' => 'nullable|array',
            'rent' => 'nullable|array',
            'partnerships' => 'nullable|array',
            'annuities' => 'nullable|array',
            'superannuation' => 'nullable|array',
            'super_lump_sums' => 'nullable|array',
            'ess' => 'nullable|array',
            'personal_services' => 'nullable|array',
            'business_income' => 'nullable|array',
            'business_losses' => 'nullable|array',
            'foreign_income' => 'nullable|array',
            'other_income' => 'nullable|array',

            'other_income.info' => 'nullable|array',
            'other_income.info.*.other_income_type' => 'nullable|string',
            'other_income.info.*.other_income_amount' => 'nullable|numeric',
            'other_income.info.*.bal_adj_financial' => 'nullable|numeric',
            'other_income.info.*.bal_adj_rental' => 'nullable|numeric',
            'other_income.info.*.bal_adj_remaining' => 'nullable|numeric',
            'other_income.fhss_amount' => 'nullable|numeric',
            'other_income.fhss_tax_withheld' => 'nullable|numeric',
            'other_income.professional_income' => 'nullable|numeric',

            // File upload rules
            'capital_gains.cgt_attachment' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'managed_fund_files.*' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'termination_payments.*.etp_files.*' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'rent.*.rent_files.*' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ];



        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors'  => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();


        // Normalize super lump sums
        if (!empty($validated['super_lump_sums'])) {
            $sums = $validated['super_lump_sums'];
            $validated['super_lump_sums'] = [
                'lump_sum_count' => $sums['lump_sum_count'] ?? count($sums['payments'] ?? []),
                'payments' => array_values($sums['payments'] ?? []),
            ];
        }

        // Fix issue where empty capital_gains is ignored but input exists
        if ($request->has('capital_gains') && empty($validated['capital_gains'])) {
            $validated['capital_gains'] = $request->input('capital_gains');
        }

        $existing = $id ? Income::findOrFail($id) : null;

        // Get all top-level fields
        $fields = collect(array_keys($rules))
            ->reject(fn($key) => str_contains($key, '.'))
            ->toArray();

        $data = [];
        foreach ($fields as $field) {
            $existingValue = $existing
                ? (is_array($existing->$field) ? $existing->$field : json_decode($existing->$field, true))
                : [];

            $newValue = $validated[$field] ?? [];

            // Merge existing and new values recursively
            $merged = array_replace_recursive($existingValue ?: [], $newValue ?: []);

            // Store null if empty
            $data[$field] = empty($merged) ? null : $merged;
        }

        // Handle attachments
        $attach = $existing ? ($existing->attach ?? []) : [];

        $this->fileService->handleCapitalGainsFiles($request, $attach, $data);
        $this->fileService->handleManagedFundsFiles($request, $attach, $data);
        $this->fileService->handleTerminationPaymentsFiles($request, $attach, $data);
        $this->fileService->handleRentFiles($request, $attach, $data);

        $data['attach'] = empty($attach) ? null : $attach;


        if ($request->has('other_income')) {
            $data['other_income'] = $request->input('other_income');
        }

        if ($request->has('salary')) {
            $data['salary'] = $request->input('salary');
        }

        if ($request->has('interests')) {
            $data['interests'] = $request->input('interests');
        }

        if ($request->has('government_pensions')) {
            $data['government_pensions'] = $request->input('government_pensions');
        }


        if ($existing) {
            $existing->update($data);
            $message = 'Income data updated successfully!';
            $incomeId = $existing->id;
        } else {
            $income = Income::create(array_merge($data, [
                'tax_return_id' => $taxReturn->id,
            ]));
            $message = 'Income data saved successfully!';
            $incomeId = $income->id;
        }

        return response()->json([
            'success'  => true,
            'message'  => $message,
            'incomeId' => $incomeId,
        ]);
    }




    /**
     * @param Request $request
     * @param string $taxId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, string $taxId)
    {
        return $this->saveIncome($request, $taxId);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $taxId, string $id)
    {
        return $this->saveIncome($request, $taxId, $id);
    }
}
