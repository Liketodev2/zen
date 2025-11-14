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
            'home_office.home_receipt'      => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'books.books_file'              => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'uniforms.uniform_receipt'      => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'education.edu_file'            => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'union_fees.file'               => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'sun_protection.sun_file'       => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'low_value_pool.files.*'        => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ];

        $validated = $request->validate($rules);

        $existing = $id
            ? Deduction::findOrFail($id)
            : Deduction::firstOrCreate(['tax_return_id' => $taxReturn->id]);

        // Merge top-level arrays but fully replace arrays sent in request
        $fields = collect(array_keys($rules))->reject(fn($k) => str_contains($k, '.'))->toArray();
        $data = [];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                // Replace completely if field exists in request
                $data[$field] = $request->input($field);
            } else {
                // Keep old data
                $existingValue = is_array($existing->$field)
                    ? $existing->$field
                    : (json_decode($existing->$field, true) ?? []);
                $data[$field] = empty($existingValue) ? null : $existingValue;
            }
        }

        // Handle attachments array
        $attach = is_string($existing->attach)
            ? json_decode($existing->attach, true) ?? []
            : ($existing->attach ?? []);

        // Handle single files
        $this->fileService->handleSingleFileForWeb($request, $attach, $data, 'travel_file', 'travel_expenses', 'travel_file');
        $this->fileService->handleSingleFileForWeb($request, $attach, $data, 'computer_file', 'computer', 'computer_file');
        $this->fileService->handleMultipleFilesForWeb($request, $attach, $data, 'files', 'low_value_pool', 'files');
        $this->fileService->handleSingleFileForWeb($request, $attach, $data, 'home_receipt', 'home_office', 'home_receipt');
        $this->fileService->handleSingleFileForWeb($request, $attach, $data, 'books_file', 'books', 'books_file');
        $this->fileService->handleSingleFileForWeb($request, $attach, $data, 'uniform_receipt', 'uniforms', 'uniform_receipt');
        $this->fileService->handleSingleFileForWeb($request, $attach, $data, 'edu_file', 'education', 'edu_file');
        $this->fileService->handleSingleFileForWeb($request, $attach, $data, 'file', 'union_fees', 'file');
        $this->fileService->handleSingleFileForWeb($request, $attach, $data, 'sun_file', 'sun_protection', 'sun_file');

        // ✅ Special handling for travel_expenses nested array
        if ($request->has('travel_expenses') || $request->hasFile('travel_expenses.travel_file')) {
            $existingValue = is_array($existing->travel_expenses) ? $existing->travel_expenses : [];

            // Merge expenses/payg_allowance
            $newValue = $request->input('travel_expenses', []);
            if (isset($newValue['expenses'])) {
                $existingValue['expenses'] = $newValue['expenses'];
            }
            if (isset($newValue['payg_allowance'])) {
                $existingValue['payg_allowance'] = $newValue['payg_allowance'];
            }

            // Merge uploaded file URL from attach (do not overwrite other keys)
            if (!empty($attach['travel_expenses']['travel_file'])) {
                $existingValue['travel_file'] = $attach['travel_expenses']['travel_file'];
            }

            $data['travel_expenses'] = $existingValue;
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
}
