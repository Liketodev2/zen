<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use App\Services\DeductionFileService;
use Illuminate\Http\Request;
use App\Models\Forms\Deduction;
use App\Models\TaxReturn;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DeductionController extends Controller
{


    /**
     * @var DeductionFileService
     */
    protected DeductionFileService $fileService;


    /**
     * @param DeductionFileService $fileService
     */
    public function __construct(DeductionFileService $fileService)
    {
        $this->fileService = $fileService;
    }


    /**
     * @param Request $request
     * @param $taxId
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    private function saveDeduction(Request $request, $taxId, $id = null)
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

        // Validation rules
        $rules = [
            'car_expenses'        => 'nullable|array',
            'travel_expenses'     => 'nullable|array',
            'mobile_phone'        => 'nullable|array',
            'internet_access'     => 'nullable|array',
            'computer'            => 'nullable|array',
            'gifts'               => 'nullable|array',
            'home_office'         => 'nullable|array',
            'books'               => 'nullable|array',
            'tax_affairs'         => 'nullable|array',
            'uniforms'            => 'nullable|array',
            'education'           => 'nullable|array',
            'tools'               => 'nullable|array',
            'superannuation'      => 'nullable|array',
            'office_occupancy'    => 'nullable|array',
            'union_fees'          => 'nullable|array',
            'sun_protection'      => 'nullable|array',
            'low_value_pool'      => 'nullable|array',
            'interest_deduction'  => 'nullable|array',
            'dividend_deduction'  => 'nullable|array',
            'upp'                 => 'nullable|array',
            'project_pool'        => 'nullable|array',
            'investment_scheme'   => 'nullable|array',
            'other'               => 'nullable|array',

            // File uploads
            'travel_expenses.travel_file'   => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'computer.computer_file'        => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'home_office.home_receipt_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'books.books_file'              => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'uniforms.uniform_receipt'      => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'education.edu_file'            => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'union_fees.file'               => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'sun_protection.sun_file'       => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'low_value_pool.files.*'        => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ];

        $rules['active_sections'] = 'nullable|string';
        $validated = $request->validate($rules);

        $existing = $id
            ? Deduction::findOrFail($id)
            : Deduction::firstOrCreate(['tax_return_id' => $taxReturn->id]);

        // Merge top-level arrays (universal merge to keep files)
        $fields = collect(array_keys($rules))->reject(fn($k) => str_contains($k, '.'))->toArray();
        $data = [];

        foreach ($fields as $field) {
            $existingValue = is_array($existing->$field)
                ? $existing->$field
                : (json_decode($existing->$field, true) ?? []);

            if ($request->has($field)) {
                $newValue = $request->input($field);
                if (is_array($newValue) && is_array($existingValue)) {
                    $data[$field] = array_replace_recursive($existingValue, $newValue);
                } else {
                    // If incoming is scalar or not an array, preserve it directly
                    $data[$field] = $newValue;
                }
            } else {
                $data[$field] = empty($existingValue) ? null : $existingValue;
            }
        }

        // Handle attachments array
        $attach = is_string($existing->attach)
            ? json_decode($existing->attach, true) ?? []
            : ($existing->attach ?? []);

        // Handle single/multiple files
        $this->fileService->handleSingleFileForWeb($request, $attach, $data, 'travel_file', 'travel_expenses', 'travel_file');
        $this->fileService->handleSingleFileForWeb($request, $attach, $data, 'computer_file', 'computer', 'computer_file');
        $this->fileService->handleMultipleFilesForWeb($request, $attach, $data, 'files', 'low_value_pool', 'files');
        $this->fileService->handleSingleFileForWeb($request, $attach, $data, 'home_receipt_file', 'home_office', 'home_receipt_file');
        $this->fileService->handleSingleFileForWeb($request, $attach, $data, 'books_file', 'books', 'books_file');
        $this->fileService->handleSingleFileForWeb($request, $attach, $data, 'uniform_receipt', 'uniforms', 'uniform_receipt');
        $this->fileService->handleSingleFileForWeb($request, $attach, $data, 'edu_file', 'education', 'edu_file');
        $this->fileService->handleSingleFileForWeb($request, $attach, $data, 'file', 'union_fees', 'file');
        $this->fileService->handleSingleFileForWeb($request, $attach, $data, 'sun_file', 'sun_protection', 'sun_file');

        // Special handling for travel_expenses nested array
        if ($request->has('travel_expenses') || $request->hasFile('travel_expenses.travel_file')) {
            $existingValue = is_array($existing->travel_expenses) ? $existing->travel_expenses : [];
            $newValue = $request->input('travel_expenses', []);

            if (isset($newValue['expenses'])) {
                $existingValue['expenses'] = $newValue['expenses'];
            }
            if (isset($newValue['payg_allowance'])) {
                $existingValue['payg_allowance'] = $newValue['payg_allowance'];
            }

            if (!empty($attach['travel_expenses']['travel_file'])) {
                $existingValue['travel_file'] = $attach['travel_expenses']['travel_file'];
            }

            $data['travel_expenses'] = $existingValue;
        }

        // Ensure any uploaded files for travel_expenses (or other sections) are present in $data
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


        // Cleanup inactive sections
        $toggleable = [
            'car_expenses','travel_expenses','mobile_phone','internet_access','computer','gifts','home_office',
            'books','tax_affairs','uniforms','education','tools','superannuation','office_occupancy','union_fees',
            'sun_protection','low_value_pool','interest_deduction','dividend_deduction','upp','project_pool','investment_scheme','other'
        ];

        $activeSectionsRaw = $request->input('active_sections', '[]');
        $activeSections = json_decode($activeSectionsRaw, true);
        if (!is_array($activeSections)) {
            $activeSections = [];
        }

        // If user didn't explicitly submit `active_sections`, try to detect sections from inputs/files.
        $detected = [];
        if (!$request->has('active_sections')) {
            $allInputKeys = array_keys($request->all());
            foreach ($toggleable as $section) {
                if ($request->has($section) || $request->hasFile($section) || array_key_exists($section, $request->all())) {
                    $detected[] = $section;
                    continue;
                }
                // Keys like 'car_expenses[vehicles][0][work_kilometers]' appear in request->all() as full keys,
                // so check raw input keys as fallback
                foreach ($allInputKeys as $k) {
                    if (strpos($k, $section . '[') === 0) {
                        $detected[] = $section;
                        break;
                    }
                }
                // Only treat existing attachments as active when no explicit active_sections provided
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
        foreach ($toggleable as $section) {
            $hasInput = false;
            if (in_array($section, $activeSections, true)) {
                $hasInput = true;
            }
            // Check direct presence
            if (!$hasInput && ($request->has($section) || array_key_exists($section, $request->all()))) {
                $hasInput = true;
            }
            // Check nested keys like 'car_expenses[vehicles][0][work_kilometers]'
            if (!$hasInput) {
                foreach ($allInputKeys as $k) {
                    if (strpos($k, $section . '[') === 0) {
                        $hasInput = true;
                        break;
                    }
                }
            }
            // Check files. Treat existing stored attachments as active ONLY when the client did NOT
            // explicitly send `active_sections`. If client did send `active_sections`, we must
            // respect it (so deselecting removes stored files).
            if (!$hasInput && $request->hasFile($section)) {
                $hasInput = true;
            }
            if (!$hasInput && !$request->has('active_sections') && !empty($attach[$section])) {
                $hasInput = true;
            }

            if (!$hasInput) {
                $data[$section] = null;
                if (!empty($attach[$section])) {
                    $this->deleteAttachSectionFiles($attach[$section]);
                    unset($attach[$section]);
                }
            }
        }

        $data['attach'] = empty($attach) ? null : $attach;

        // Save model
        $existing->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Deduction data saved successfully!',
            'deductionId' => $existing->id,
        ]);
    }


    /**
     * @param Request $request
     * @param string $taxId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, string $taxId)
    {
        return $this->saveDeduction($request, $taxId);
    }


    /**
     * @param Request $request
     * @param string $taxId
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, string $taxId, string $id)
    {
        return $this->saveDeduction($request, $taxId, $id);
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
