<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaxReturn;
use App\Models\Forms\Income;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class IncomeController extends Controller
{


    /**
     * @param Request $request
     * @param $taxId
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
//    private function saveIncome(Request $request, $taxId, $id = null)
//    {
//        $taxReturn = TaxReturn::where('user_id', auth()->id())
//            ->where('id', $taxId)
//            ->first();
//
//        if (!$taxReturn) {
//            return response()->json([
//                'success' => false,
//                'message' => 'Tax Return not found'
//            ], 404);
//        }
//
//        $rules = [
//            'salary' => 'nullable|array',
//            'interests' => 'nullable|array',
//            'dividends' => 'nullable|array',
//            'government_allowances' => 'nullable|array',
//            'government_pensions' => 'nullable|array',
//
//            'capital_gains' => 'nullable|array',
//            'capital_gains.cgt_attachment' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
//
//            'managed_funds' => 'nullable|array',
//            'managed_fund_files.*' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
//
//            'termination_payments' => 'nullable|array',
//            'termination_payments.*.etp_files.*' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
//
//            'rent' => 'nullable|array',
//            'rent.*.rent_files.*' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
//
//            'partnerships' => 'nullable|array',
//            'annuities' => 'nullable|array',
//            'superannuation' => 'nullable|array',
//            'super_lump_sums' => 'nullable|array',
//            'ess' => 'nullable|array',
//            'personal_services' => 'nullable|array',
//            'business_income' => 'nullable|array',
//            'business_losses' => 'nullable|array',
//            'foreign_income' => 'nullable|array',
//            'other_income' => 'nullable|array',
//        ];
//
//        $validator = Validator::make($request->all(), $rules);
//
//        if ($validator->fails()) {
//            return response()->json([
//                'success' => false,
//                'message' => 'Validation errors',
//                'errors'  => $validator->errors()
//            ], 422);
//        }
//
//        $validated = $validator->validated();
//
//        if (!empty($validated['super_lump_sums'])) {
//            $sums = $validated['super_lump_sums'];
//            $validated['super_lump_sums'] = [
//                'lump_sum_count' => $sums['lump_sum_count'] ?? count($sums['payments'] ?? []),
//                'payments' => array_values($sums['payments'] ?? [])
//            ];
//        }
//
//        if ($request->has('capital_gains') && empty($validated['capital_gains'])) {
//            $validated['capital_gains'] = $request->input('capital_gains');
//        }
//
//        $existing = $id ? Income::findOrFail($id) : null;
//
//        // Collect all top-level array fields (ignore dot rules)
//        $fields = collect(array_keys($rules))
//            ->reject(fn($key) => str_contains($key, '.'))
//            ->toArray();
//
//        $data = [];
//        foreach ($fields as $field) {
//            $existingValue = $existing ? (is_array($existing->$field) ? $existing->$field : json_decode($existing->$field, true)) : [];
//            $newValue = $validated[$field] ?? [];
//            $data[$field] = array_replace_recursive($existingValue ?: [], $newValue ?: []);
//        }
//
//        $attach = $existing ? ($existing->attach ?? []) : [];
//
//        $this->handleCapitalGainsFiles($request, $attach, $data);
//        $this->handleManagedFundsFiles($request, $attach, $data);
//        $this->handleTerminationPaymentsFiles($request, $attach, $data);
//        $this->handleRentFiles($request, $attach, $data);
//
//        $data['attach'] = $attach;
//
//        if ($existing) {
//            $existing->update($data);
//            $message = 'Income data updated successfully!';
//            $incomeId = $existing->id;
//        } else {
//            $income = Income::create(array_merge($data, [
//                'tax_return_id' => $taxReturn->id
//            ]));
//            $message = 'Income data saved successfully!';
//            $incomeId = $income->id;
//        }
//
//        return response()->json([
//            'success'  => true,
//            'message'  => $message,
//            'incomeId' => $incomeId
//        ]);
//    }

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
            'capital_gains.cgt_attachment' => 'nullable|file|mimes:pdf,jpg,png|max:5120',

            'managed_funds' => 'nullable|array',
            'managed_fund_files.*' => 'nullable|file|mimes:pdf,jpg,png|max:5120',

            'termination_payments' => 'nullable|array',
            'termination_payments.*.etp_files.*' => 'nullable|file|mimes:pdf,jpg,png|max:5120',

            'rent' => 'nullable|array',
            'rent.*.rent_files.*' => 'nullable|file|mimes:pdf,jpg,png|max:5120',

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
                'payments' => array_values($sums['payments'] ?? [])
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

            // Merge existing and new values
            $merged = array_replace_recursive($existingValue ?: [], $newValue ?: []);

            // Store null if empty
            $data[$field] = empty($merged) ? null : $merged;
        }

        // Handle attachments
        $attach = $existing ? ($existing->attach ?? []) : [];

        $this->handleCapitalGainsFiles($request, $attach, $data);
        $this->handleManagedFundsFiles($request, $attach, $data);
        $this->handleTerminationPaymentsFiles($request, $attach, $data);
        $this->handleRentFiles($request, $attach, $data);

        $data['attach'] = empty($attach) ? null : $attach;

        if ($existing) {
            $existing->update($data);
            $message = 'Income data updated successfully!';
            $incomeId = $existing->id;
        } else {
            $income = Income::create(array_merge($data, [
                'tax_return_id' => $taxReturn->id
            ]));
            $message = 'Income data saved successfully!';
            $incomeId = $income->id;
        }

        return response()->json([
            'success'  => true,
            'message'  => $message,
            'incomeId' => $incomeId
        ]);
    }





    /**
     * Handle Capital Gains File Upload
     */
    private function handleCapitalGainsFiles(Request $request, array &$attach, array &$data)
    {
        if ($request->hasFile('capital_gains.cgt_attachment')) {

            // Delete old file if exists
            if (!empty($attach['capital_gains_attachment'])) {
                Storage::disk('s3')->delete($attach['capital_gains_attachment']);
            }

            // Store new file
            $file = $request->file('capital_gains.cgt_attachment');
            $path = $file->store('capital_gains', 's3');

            // Update both attach & data
            $attach['capital_gains_attachment'] = $path;
            $data['capital_gains']['cgt_attachment'] = $path;
        }
    }


    /**
     * Handle Managed Funds Files
     */
    private function handleManagedFundsFiles(Request $request, array &$attach, array &$data)
    {
        if ($request->hasFile('managed_fund_files')) {

            // Delete old files
            if (!empty($attach['managed_fund_files'])) {
                foreach ($attach['managed_fund_files'] as $oldFile) {
                    Storage::disk('s3')->delete($oldFile);
                }
            }

            // Store new files
            $files = $request->file('managed_fund_files');
            $paths = [];
            foreach ($files as $file) {
                $paths[] = $file->store('managed_funds', 's3');
            }

            // Update both attach & data
            $attach['managed_fund_files'] = $paths;
            $data['managed_funds']['managed_fund_files'] = $paths;
        }
    }


    /**
     * Handle Termination Payments Files
     */
    private function handleTerminationPaymentsFiles(Request $request, array &$attach, array &$data)
    {
        $etpFiles = $request->file('termination_payments', []);

        foreach ($etpFiles as $index => $files) {
            if ($request->hasFile("termination_payments.$index.etp_files")) {

                // Delete old files
                if (!empty($attach['termination_payments'][$index]['etp_files'])) {
                    foreach ($attach['termination_payments'][$index]['etp_files'] as $oldFile) {
                        Storage::disk('s3')->delete($oldFile);
                    }
                }

                // Store new files
                $paths = [];
                foreach ($files['etp_files'] as $file) {
                    $paths[] = $file->store('termination_payments', 's3');
                }

                // Update both attach & data
                $attach['termination_payments'][$index]['etp_files'] = $paths;
                $data['termination_payments'][$index]['etp_files'] = $paths;
            }
        }
    }


    /**
     * Handle Rent Files
     */
    private function handleRentFiles(Request $request, array &$attach, array &$data)
    {
        $rentFiles = $request->file('rent', []);

        foreach ($rentFiles as $index => $files) {
            if ($request->hasFile("rent.$index.rent_files")) {

                // Delete old files
                if (!empty($attach['rent'][$index]['rent_files'])) {
                    foreach ($attach['rent'][$index]['rent_files'] as $oldFile) {
                        Storage::disk('s3')->delete($oldFile);
                    }
                }

                // Store new files
                $paths = [];
                foreach ($files['rent_files'] as $file) {
                    $paths[] = $file->store('rent', 's3');
                }

                // Update both attach & data
                $attach['rent'][$index]['rent_files'] = $paths;
                $data['rent'][$index]['rent_files'] = $paths;
            }
        }
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
