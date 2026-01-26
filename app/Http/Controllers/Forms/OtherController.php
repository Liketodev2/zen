<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use App\Models\Forms\Other;
use App\Services\OtherFileService;
use Illuminate\Http\Request;
use App\Models\TaxReturn;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class OtherController extends Controller
{


    /**
     * @var OtherFileService
     */
    protected OtherFileService $fileService;


    /**
     * @param OtherFileService $fileService
     */
    public function __construct(OtherFileService $fileService)
    {
        $this->fileService = $fileService;
    }


    /**
     * @param Request $request
     * @param $taxId
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    private function saveOther(Request $request, $taxId, $id = null)
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

        /**
         * Validation rules
         */
        $rules = [
            'any_dependent_children'               => 'nullable|string|max:255',
            'additional_questions'                 => 'nullable|string|max:255',

            'income_tests'                         => 'nullable|array',
            'mls'                                  => 'nullable|array',
            'spouse_details'                       => 'nullable|array',
            'zone_overseas_forces_offset'          => 'nullable|array',
            'seniors_offset'                       => 'nullable|array',
            'part_year_tax_free_threshold'         => 'nullable|array',
            'under_18'                             => 'nullable|array',
            'working_holiday_maker_net_income'     => 'nullable|array',
            'superannuation_income_stream_offset'  => 'nullable|array',
            'superannuation_contributions_spouse'  => 'nullable|array',
            'tax_losses_earlier_income_years'      => 'nullable|array',
            'dependent_invalid_and_carer'          => 'nullable|array',
            'superannuation_co_contribution'       => 'nullable|array',
            'other_tax_offsets_refundable'         => 'nullable|array',

            // With files
            'private_health_insurance'             => 'nullable|array',
            'medicare_reduction_exemption'         => 'nullable|array',
            'medical_expenses_offset'              => 'nullable|array',

            // File validations
            'documents_to_attach_files.*'          => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'private_health_insurance.statement_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'private_health_insurance.private_health_statement' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'medicare_reduction_exemption.medicare_certificate_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'medical_expenses_offset.medical_expense_file'  => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ];

        $rules['active_sections'] = 'nullable|string';
        $validated = $request->validate($rules);

        /**
         * Load or create model
         */
        $other = $id
            ? Other::findOrFail($id)
            : Other::firstOrCreate(['tax_return_id' => $taxReturn->id]);

        /**
         * Merge array & scalar fields (same as DeductionController)
         */
        $data = [];

        $fields = collect(array_keys($rules))
            ->reject(fn ($key) => str_contains($key, '.'))
            ->toArray();

        foreach ($fields as $field) {
            $existingValue = is_array($other->$field)
                ? $other->$field
                : (json_decode($other->$field, true) ?? null);

            if ($request->has($field)) {
                $incoming = $request->input($field);

                // Special handling for private_health_insurance based on input_option
                if ($field === 'private_health_insurance' && is_array($incoming)) {
                    $inputOption = $incoming['input_option'] ?? null;
                    
                    // If input_option changed, clear data from other options
                    if ($inputOption === 'Let Etax collect my details') {
                        // Clear statements and file-related fields
                        $data[$field] = [
                            'family_status' => $incoming['family_status'] ?? null,
                            'dependent_children' => $incoming['dependent_children'] ?? null,
                            'input_option' => $inputOption
                        ];
                    } elseif ($inputOption === 'Attach my statement') {
                        // Clear statements, keep file-related fields
                        $data[$field] = [
                            'family_status' => $incoming['family_status'] ?? null,
                            'dependent_children' => $incoming['dependent_children'] ?? null,
                            'input_option' => $inputOption
                        ];
                    } elseif ($inputOption === 'Enter my details myself') {
                        // Keep statements, clear unnecessary fields
                        $data[$field] = [
                            'family_status' => $incoming['family_status'] ?? null,
                            'dependent_children' => $incoming['dependent_children'] ?? null,
                            'input_option' => $inputOption,
                            'statements' => $incoming['statements'] ?? []
                        ];
                    } else {
                        // No option selected or other case - use default merge
                        $data[$field] = $incoming;
                    }
                } elseif (is_array($existingValue) && is_array($incoming)) {
                    // Check if incoming array has numeric keys (like statements array)
                    // If so, replace completely instead of merging
                    $hasNumericKeys = !empty($incoming) && array_keys($incoming) === range(0, count($incoming) - 1);
                    
                    if ($hasNumericKeys) {
                        // Replace completely for sequential arrays
                        $data[$field] = $incoming;
                    } else {
                        // Merge associative arrays, but check for nested numeric arrays
                        $merged = $existingValue;
                        foreach ($incoming as $key => $value) {
                            if (is_array($value) && !empty($value) && array_keys($value) === range(0, count($value) - 1)) {
                                // Replace numeric arrays completely
                                $merged[$key] = $value;
                            } else {
                                // Merge other values
                                $merged[$key] = $value;
                            }
                        }
                        $data[$field] = $merged;
                    }
                } else {
                    // scalar, null, or first-time value
                    $data[$field] = $incoming;
                }
            } else {
                // field not sent → preserve existing value
                $data[$field] = $existingValue;
            }
        }

        /**
         * Attachments (preserve + merge)
         */
        $attach = is_array($other->attach)
            ? $other->attach
            : (json_decode($other->attach, true) ?? []);

        /**
         * Handle private_health_insurance file cleanup based on input_option
         */
        if ($request->has('private_health_insurance.input_option')) {
            $inputOption = $request->input('private_health_insurance.input_option');
            
            // Delete files based on the selected option
            if (!empty($attach['private_health_insurance'])) {
                $filesToDelete = [];

                // Determine which files to delete based on current option
                if ($inputOption === 'Let Etax collect my details') {
                    // Delete ALL files - this option doesn't use any files
                    if (!empty($attach['private_health_insurance']['statement_file'])) {
                        $filesToDelete[] = 'statement_file';
                    }
                    if (!empty($attach['private_health_insurance']['private_health_statement'])) {
                        $filesToDelete[] = 'private_health_statement';
                    }
                } elseif ($inputOption === 'Attach my statement') {
                    // Delete private_health_statement only, keep statement_file
                    if (!empty($attach['private_health_insurance']['private_health_statement'])) {
                        $filesToDelete[] = 'private_health_statement';
                    }
                } elseif ($inputOption === 'Enter my details myself') {
                    // Delete statement_file only, keep private_health_statement
                    if (!empty($attach['private_health_insurance']['statement_file'])) {
                        $filesToDelete[] = 'statement_file';
                    }
                }

                // Delete the files
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
        }

        /**
         * File handling via OtherFileService
         */
        $this->fileService->handleAdditionalFiles($request, $attach, $data);
        $this->fileService->handlePrivateHealthInsuranceFiles($request, $attach);
        $this->fileService->handleMedicareCertificate($request, $attach, $data);
        $this->fileService->handleMedicalExpenseFile($request, $attach, $data);

        /**
         * Cleanup: null out inactive sections and delete their files
         */
        $toggleableSections = [
            'spouse_details',
            'private_health_insurance',
            'zone_overseas_forces_offset',
            'seniors_offset',
            'medicare_reduction_exemption',
            'part_year_tax_free_threshold',
            'medical_expenses_offset',
            'under_18',
            'working_holiday_maker_net_income',
            'superannuation_income_stream_offset',
            'superannuation_contributions_spouse',
            'tax_losses_earlier_income_years',
            'dependent_invalid_and_carer',
            'superannuation_co_contribution',
            'other_tax_offsets_refundable',
        ];

        $activeSectionsRaw = $request->input('active_sections', '[]');
        $activeSections     = json_decode($activeSectionsRaw, true);
        if (!is_array($activeSections)) {
            $activeSections = [];
        }

        // If `active_sections` not explicitly provided, detect incoming data/files and treat those sections as active.
        $detected = [];
        if (!$request->has('active_sections')) {
            $allInputKeys = array_keys($request->all());
            foreach ($toggleableSections as $section) {
                if ($request->has($section) || $request->hasFile($section) || array_key_exists($section, $request->all())) {
                    $detected[] = $section;
                    continue;
                }
                foreach ($allInputKeys as $k) {
                    if (strpos($k, $section . '[') === 0) {
                        $detected[] = $section;
                        break;
                    }
                }
                if (!empty($attach[$section])) {
                    $detected[] = $section;
                }
            }

            $activeSections = array_values(array_unique($detected));
        }

        // NOTE: Respect explicit `active_sections` sent by client. Auto-detection only runs when
        // the client did not send `active_sections` at all.

        // Finalize active status per section: section is active if it's listed in activeSections
        // OR if the request contains keys/files for that section or there are existing attachments.
        $allInputKeys = array_keys($request->all());
        foreach ($toggleableSections as $section) {
            $hasInput = false;
            if (in_array($section, $activeSections, true)) {
                $hasInput = true;
            }
            if (!$hasInput && ($request->has($section) || array_key_exists($section, $request->all()))) {
                $hasInput = true;
            }
            if (!$hasInput) {
                foreach ($allInputKeys as $k) {
                    if (strpos($k, $section . '[') === 0) {
                        $hasInput = true;
                        break;
                    }
                }
            }
            if (!$hasInput && $request->hasFile($section)) {
                $hasInput = true;
            }
            if (!$hasInput && !$request->has('active_sections') && !empty($attach[$section])) {
                $hasInput = true;
            }

            if (!$hasInput) {
                $data[$section] = null;

                // Delete and remove any attached files for this section
                if (!empty($attach[$section])) {
                    $this->deleteAttachSectionFiles($attach[$section]);
                    unset($attach[$section]);
                }
            }
        }

        // Ensure any uploaded files are merged into the corresponding section data
        if (!empty($attach) && is_array($attach)) {
            foreach ($attach as $section => $vals) {
                if (!is_array($vals)) continue;
                if (isset($data[$section]) && is_array($data[$section])) {
                    $data[$section] = array_replace_recursive($data[$section], $vals);
                } else {
                    $data[$section] = $vals;
                }
            }
        }

        $data['attach'] = empty($attach) ? null : $attach;

        /**
         * Save model
         */
        $other->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Other data saved successfully!',
            'otherId' => $other->id
        ]);
    }




    /**
     * @param Request $request
     * @param string $taxId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, string $taxId)
    {
        return $this->saveOther($request, $taxId);
    }


    /**
     * @param Request $request
     * @param string $
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $taxId, string $id)
    {
        return $this->saveOther($request, $taxId, $id);
    }

    /**
     * Recursively delete files referenced in an attachment section
     * @param mixed $sectionAttach
     * @return void
     */
    private function deleteAttachSectionFiles($sectionAttach): void
    {
        if (is_array($sectionAttach)) {
            foreach ($sectionAttach as $value) {
                $this->deleteAttachSectionFiles($value);
            }
            return;
        }

        if (is_string($sectionAttach) && $sectionAttach !== '') {
            $this->fileService->deleteFile($sectionAttach);
        }
    }
}
