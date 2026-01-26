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
    public function saveIncome(Request $request, $taxId, $id = null)
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

        $rules['active_sections'] = 'nullable|string';

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors'  => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        // Process active_sections
        $activeSectionsRaw = $request->input('active_sections', '[]');
        $activeSections = json_decode($activeSectionsRaw, true);
        if (!is_array($activeSections)) {
            $activeSections = [];
        }


        // Normalize super lump sums
        if (!empty($validated['super_lump_sums'])) {
            $sums = $validated['super_lump_sums'];
            $validated['super_lump_sums'] = [
                'lump_sum_count' => $sums['lump_sum_count'] ?? count($sums['payments'] ?? []),
                'payments' => array_values($sums['payments'] ?? []),
            ];
        }

        // Fix empty capital gains if input exists
        if ($request->has('capital_gains') && empty($validated['capital_gains'])) {
            $validated['capital_gains'] = $request->input('capital_gains');
        }

        $existing = $id ? Income::findOrFail($id) : Income::firstOrCreate(['tax_return_id' => $taxReturn->id]);

        // Get top-level fields (ignore dot notation fields)
        $fields = collect(array_keys($rules))
            ->reject(fn($key) => str_contains($key, '.'))
            ->toArray();

        $data = [];
        foreach ($fields as $field) {
            $existingValue = $existing->$field;
            $existingValue = is_array($existingValue) ? $existingValue : json_decode($existingValue, true) ?? [];
            $newValue = $validated[$field] ?? [];

            if (!is_array($newValue)) {
                // Preserve scalar values directly
                $data[$field] = $newValue === [] ? (empty($existingValue) ? null : $existingValue) : $newValue;
            } else {
                $merged = array_replace_recursive(is_array($existingValue) ? $existingValue : [], $newValue);
                $data[$field] = empty($merged) ? null : $merged;
            }
        }

        // Handle attachments
        $attach = is_string($existing->attach) ? json_decode($existing->attach, true) ?? [] : ($existing->attach ?? []);

        $this->fileService->handleCapitalGainsFiles($request, $attach, $data);
        $this->fileService->handleManagedFundsFiles($request, $attach, $data);
        $this->fileService->handleTerminationPaymentsFiles($request, $attach, $data);

        // Handle rent: replace all rent data and delete old files
        if ($request->has('rent')) {
            $data['rent'] = $request->input('rent', []);
        }

        $this->fileService->handleRentFiles($request, $attach, $data);

        $data['attach'] = empty($attach) ? null : $attach;

        // Cleanup inactive sections: null data and delete files
        $toggleable = [
            'salary','interests','dividends','government_allowances','government_pensions','capital_gains',
            'managed_funds','termination_payments','rent','partnerships','annuities','superannuation',
            'super_lump_sums','ess','personal_services','business_income','business_losses','foreign_income','other_income'
        ];

        foreach ($toggleable as $section) {
            if (!in_array($section, $activeSections, true)) {
                $data[$section] = null;
                if (!empty($attach[$section])) {
                    $this->deleteAttachSectionFiles($attach[$section]);
                    unset($attach[$section]);
                }
            }
        }

        $data['attach'] = empty($attach) ? null : $attach;

        // Save to database
        $existing->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Income data saved successfully!',
            'incomeId' => $existing->id,
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

    /**
     * Helper to recursively delete files in attach sections for IncomeController
     */
    private function deleteAttachSectionFiles($sectionAttach): void
    {
        if (is_array($sectionAttach)) {
            foreach ($sectionAttach as $value) {
                if (is_array($value)) {
                    $this->deleteAttachSectionFiles($value);
                } elseif (is_string($value) && $value !== '') {
                    Storage::disk('s3')->delete($value);
                }
            }
            return;
        }

        if (is_string($sectionAttach) && $sectionAttach !== '') {
            Storage::disk('s3')->delete($sectionAttach);
        }
    }
}
