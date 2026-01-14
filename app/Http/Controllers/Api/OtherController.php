<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeductionResource;
use App\Http\Resources\OtherResource;
use App\Models\Forms\Deduction;
use App\Models\Forms\Other;
use App\Models\TaxReturn;
use App\Services\OtherFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OtherController extends Controller
{
    /**
     * @var OtherFileService
     */
    protected $fileService;


    /**
     * @param OtherFileService $fileService
     */
    public function __construct(OtherFileService $fileService)
    {
        $this->fileService = $fileService;
    }


    /**
     * @param Request $request
     * @return OtherResource|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function other(Request $request)
    {
        /**
         * Fields that are stored as STRING
         */
        $stringFields = [
            'any_dependent_children',
            'additional_questions',
        ];

        /**
         * Fields that are stored as ARRAY
         */
        $arrayFields = [
            'income_tests',
            'mls',
            'spouse_details',
            'zone_overseas_forces_offset',
            'seniors_offset',
            'part_year_tax_free_threshold',
            'under_18',
            'working_holiday_maker_net_income',
            'superannuation_income_stream_offset',
            'superannuation_contributions_spouse',
            'tax_losses_earlier_income_years',
            'dependent_invalid_and_carer',
            'superannuation_co_contribution',
            'other_tax_offsets_refundable',
        ];

        /**
         * Normalize JSON strings → arrays
         */
        foreach ($arrayFields as $field) {
            if ($request->has($field) && is_string($request->$field)) {
                $decoded = json_decode($request->$field, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $request->merge([$field => $decoded]);
                }
            }
        }

        /**
         * Validation rules
         */
        $rules = [
            'tax_id' => 'required|exists:tax_returns,id',
        ];

        foreach ($stringFields as $field) {
            $rules[$field] = 'nullable|string|max:255';
        }

        foreach ($arrayFields as $field) {
            $rules[$field] = 'nullable|array';
        }

        $validated = $request->validate($rules);

        /**
         * Get tax return (ownership protected)
         */
        $taxReturn = TaxReturn::where('id', $validated['tax_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$taxReturn) {
            return response()->json([
                'error' => 'Tax return not found!',
                'validation' => false
            ], 404);
        }

        /**
         * Load or create Other model
         */
        $other = Other::firstOrCreate([
            'tax_return_id' => $taxReturn->id
        ]);

        /**
         * Decode existing attach safely
         */
        $attach = is_string($other->attach)
            ? json_decode($other->attach, true) ?: []
            : ($other->attach ?? []);

        /**
         * Merge logic:
         * - If field is sent → overwrite
         * - If NOT sent → keep existing value
         */
        $data = [];

        foreach ($stringFields as $field) {
            $data[$field] = $request->has($field)
                ? $validated[$field]
                : $other->$field;
        }

        foreach ($arrayFields as $field) {
            $existing = is_array($other->$field)
                ? $other->$field
                : (json_decode($other->$field, true) ?? null);

            if ($request->has($field)) {
                if (is_array($validated[$field])) {
                    $data[$field] = is_array($existing)
                        ? array_replace_recursive($existing, $validated[$field])
                        : $validated[$field];
                } else {
                    $data[$field] = null;
                }
            } else {
                $data[$field] = $existing;
            }
        }

        /**
         * Save
         */
        $data['attach'] = empty($attach) ? null : $attach;

        $other->update($data);

        return (new OtherResource($other->fresh()))
            ->additional([
                'success' => true,
                'message' => 'Other data saved successfully!',
            ]);
    }




    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function savePrivateHealthInsurance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tax_id' => 'required|exists:tax_returns,id',
            'private_health_insurance' => 'nullable|array',
            'private_health_insurance.statement_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'private_health_insurance.private_health_statement' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'validation' => true], 422);
        }

        $taxReturn = TaxReturn::where('id', $request->tax_id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$taxReturn) {
            return response()->json(['error' => 'Tax return not found!', 'validation' => false], 404);
        }

        $other = Other::firstOrCreate(['tax_return_id' => $taxReturn->id]);
        $attach = is_string($other->attach) ? json_decode($other->attach, true) ?: [] : ($other->attach ?? []);
        $data = $other->toArray();

        $existing = is_array($data['private_health_insurance']) ? $data['private_health_insurance'] : [];
        $incoming = $request->input('private_health_insurance') ?? [];
        $incoming = is_array($incoming) ? $incoming : [];
        
        // Handle input_option logic
        $inputOption = $incoming['input_option'] ?? null;
        
        if ($inputOption === 'Let Etax collect my details') {
            // Only keep basic fields
            $data['private_health_insurance'] = [
                'family_status' => $incoming['family_status'] ?? null,
                'dependent_children' => $incoming['dependent_children'] ?? null,
                'input_option' => $inputOption
            ];
        } elseif ($inputOption === 'Attach my statement') {
            // Keep basic fields, no statements
            $data['private_health_insurance'] = [
                'family_status' => $incoming['family_status'] ?? null,
                'dependent_children' => $incoming['dependent_children'] ?? null,
                'input_option' => $inputOption
            ];
        } elseif ($inputOption === 'Enter my details myself') {
            // Keep basic fields and statements, replace statements array completely
            $data['private_health_insurance'] = [
                'family_status' => $incoming['family_status'] ?? null,
                'dependent_children' => $incoming['dependent_children'] ?? null,
                'input_option' => $inputOption,
                'statements' => $incoming['statements'] ?? []
            ];
        } else {
            // No option or default merge
            $data['private_health_insurance'] = array_merge($existing, $incoming);
        }

        // Delete files based on input_option
        if ($inputOption && !empty($attach['private_health_insurance'])) {
            $filesToDelete = [];

            if ($inputOption === 'Let Etax collect my details') {
                // Delete ALL files
                if (!empty($attach['private_health_insurance']['statement_file'])) {
                    $filesToDelete[] = 'statement_file';
                }
                if (!empty($attach['private_health_insurance']['private_health_statement'])) {
                    $filesToDelete[] = 'private_health_statement';
                }
            } elseif ($inputOption === 'Attach my statement') {
                // Delete private_health_statement only
                if (!empty($attach['private_health_insurance']['private_health_statement'])) {
                    $filesToDelete[] = 'private_health_statement';
                }
            } elseif ($inputOption === 'Enter my details myself') {
                // Delete statement_file only
                if (!empty($attach['private_health_insurance']['statement_file'])) {
                    $filesToDelete[] = 'statement_file';
                }
            }

            foreach ($filesToDelete as $fileKey) {
                if (is_array($attach['private_health_insurance'][$fileKey])) {
                    foreach ($attach['private_health_insurance'][$fileKey] as $oldFile) {
                        $this->fileService->deleteFile($oldFile);
                    }
                } else {
                    $this->fileService->deleteFile($attach['private_health_insurance'][$fileKey]);
                }
                unset($attach['private_health_insurance'][$fileKey]);
            }
        }

        $this->fileService->handlePrivateHealthInsuranceFilesForApi($request, $attach, $data);

        $other->update([
            'private_health_insurance' => $data['private_health_insurance'],
            'attach' => $attach,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Private health insurance saved successfully!',
            'data' => new OtherResource($other->fresh()),
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveMedicareReductionExemption(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tax_id' => 'required|exists:tax_returns,id',
            'medicare_reduction_exemption' => 'nullable|array',
            'medicare_reduction_exemption.medicare_certificate_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'validation' => true], 422);
        }

        $taxReturn = TaxReturn::where('id', $request->tax_id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$taxReturn) {
            return response()->json(['error' => 'Tax return not found!', 'validation' => false], 404);
        }

        $other = Other::firstOrCreate(['tax_return_id' => $taxReturn->id]);
        $attach = is_string($other->attach) ? json_decode($other->attach, true) ?: [] : ($other->attach ?? []);
        $data = $other->toArray();

        $existing = is_array($data['medicare_reduction_exemption']) ? $data['medicare_reduction_exemption'] : [];
        $incoming = $request->input('medicare_reduction_exemption') ?? [];
        $incoming = is_array($incoming) ? $incoming : [];
        $data['medicare_reduction_exemption'] = array_merge($existing, $incoming);

        $this->fileService->handleMedicareReductionExemptionFilesForApi($request, $attach, $data);

        $other->update([
            'medicare_reduction_exemption' => $data['medicare_reduction_exemption'],
            'attach' => $attach,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Medicare reduction exemption saved successfully!',
            'data' => new OtherResource($other->fresh()),
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveMedicalExpensesOffset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tax_id' => 'required|exists:tax_returns,id',
            'medical_expenses_offset' => 'nullable|array',
            'medical_expenses_offset.medical_expense_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'validation' => true], 422);
        }

        $taxReturn = TaxReturn::where('id', $request->tax_id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$taxReturn) {
            return response()->json(['error' => 'Tax return not found!', 'validation' => false], 404);
        }

        $other = Other::firstOrCreate(['tax_return_id' => $taxReturn->id]);
        $attach = is_string($other->attach) ? json_decode($other->attach, true) ?: [] : ($other->attach ?? []);
        $data = $other->toArray();

        $existing = is_array($data['medical_expenses_offset']) ? $data['medical_expenses_offset'] : [];
        $incoming = $request->input('medical_expenses_offset') ?? [];
        $incoming = is_array($incoming) ? $incoming : [];
        $data['medical_expenses_offset'] = array_merge($existing, $incoming);

        $this->fileService->handleMedicalExpensesOffsetFilesForApi($request, $attach, $data);

        $other->update([
            'medical_expenses_offset' => $data['medical_expenses_offset'],
            'attach' => $attach,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Medical expenses offset saved successfully!',
            'data' => new OtherResource($other->fresh()),
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveDocumentsToAttach(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tax_id' => 'required|exists:tax_returns,id',
            'documents_to_attach_files.*' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'validation' => true], 422);
        }

        $taxReturn = TaxReturn::where('id', $request->tax_id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$taxReturn) {
            return response()->json(['error' => 'Tax return not found!', 'validation' => false], 404);
        }

        $other = Other::firstOrCreate(['tax_return_id' => $taxReturn->id]);
        $attach = is_string($other->attach) ? json_decode($other->attach, true) ?: [] : ($other->attach ?? []);
        $data = $other->toArray();

        // Ensure documents_to_attach_files exists in attach array
        if (empty($attach['documents_to_attach_files'])) {
            $attach['documents_to_attach_files'] = [];
        }

        $this->fileService->handleDocumentsToAttachFilesForApi($request, $attach, $data);

        $other->update([
            'attach' => $attach,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Documents attached successfully!',
            'data' => new OtherResource($other->fresh()),
        ]);
    }
}
