<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Forms\Deduction;
use App\Models\TaxReturn;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DeductionController extends Controller
{

    /**
     * @param Request $request
     * @param $taxId
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
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
            'travel_expenses.travel_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120', // 5MB
            'mobile_phone'        => 'nullable|array',
            'internet_access'     => 'nullable|array',
            'computer'            => 'nullable|array',
            'computer.computer_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120', // 5MB
            'gifts'               => 'nullable|array',
            'home_office'         => 'nullable|array',
            'home_office.home_receipt' => 'nullable|file|mimes:pdf,jpg,png|max:5120', // 5MB
            'books'               => 'nullable|array',
            'books.books_file'    => 'nullable|file|mimes:pdf,jpg,png|max:5120', // 5MB
            'tax_affairs'         => 'nullable|array',
            'uniforms'            => 'nullable|array',
            'uniforms.uniform_receipt' => 'nullable|file|mimes:pdf,jpg,png|max:5120', // 5MB
            'education'           => 'nullable|array',
            'education.edu_file'  => 'nullable|file|mimes:pdf,jpg,png|max:5120', // 5MB
            'tools'               => 'nullable|array',
            'superannuation'      => 'nullable|array',
            'office_occupancy'    => 'nullable|array',
            'union_fees'          => 'nullable|array',
            'union_fees.file'     => 'nullable|file|mimes:pdf,jpg,png|max:5120', // 5MB
            'sun_protection'      => 'nullable|array',
            'sun_protection.sun_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120', // 5MB
            'low_value_pool'      => 'nullable|array',
            'low_value_pool.files.*' => 'nullable|file|mimes:pdf,jpg,png|max:5120', // 5MB
            'interest_deduction'  => 'nullable|array',
            'dividend_deduction'  => 'nullable|array',
            'upp'                 => 'nullable|array',
            'project_pool'        => 'nullable|array',
            'investment_scheme'   => 'nullable|array',
            'other'               => 'nullable|array',
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

        $existing = $id ? Deduction::findOrFail($id) : null;

        $fields = [
            'car_expenses','travel_expenses','mobile_phone','internet_access','computer',
            'gifts','home_office','books','tax_affairs','uniforms','education','tools',
            'superannuation','office_occupancy','union_fees','sun_protection','low_value_pool',
            'interest_deduction','dividend_deduction','upp','project_pool','investment_scheme','other'
        ];

        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $validated[$field] ?? ($existing->$field ?? null);
        }

        // Keep file structure like existing
        $attach = $existing ? ($existing->attach ?? []) : [];

        // Handle file uploads
        $this->handleSingleFile($request, $attach, 'computer.computer_file', 'computer');
        $this->handleSingleFile($request, $attach, 'travel_expenses.travel_file', 'travel_expenses');
        $this->handleSingleFile($request, $attach, 'union_fees.file', 'union_fees');
        $this->handleSingleFile($request, $attach, 'sun_protection.sun_file', 'sun_protection');
        $this->handleSingleFile($request, $attach, 'education.edu_file', 'education');
        $this->handleSingleFile($request, $attach, 'uniforms.uniform_receipt', 'uniforms');
        $this->handleSingleFile($request, $attach, 'books.books_file', 'books');
        $this->handleSingleFile($request, $attach, 'home_office.home_receipt', 'home_office');

        if ($request->hasFile('low_value_pool.files')) {
            if (!empty($attach['low_value_pool']['files'])) {
                foreach ($attach['low_value_pool']['files'] as $oldFile) {
                    Storage::disk('public')->delete($oldFile);
                }
            }
            $paths = [];
            foreach ($request->file('low_value_pool.files') as $file) {
                $paths[] = $file->store('low_value_pool', 's3');
            }
            $attach['low_value_pool']['files'] = $paths;
        }

        $data['attach'] = $attach;

        if ($existing) {
            $existing->update($data);
            $deduction = $existing;
            $message = 'Deduction data updated successfully!';
        } else {
            $deduction = Deduction::create(array_merge($data, [
                'tax_return_id' => $taxReturn->id
            ]));
            $message = 'Deduction data saved successfully!';
        }

        return response()->json([
            'success'     => true,
            'message'     => $message,
            'deductionId' => $deduction->id
        ]);
    }



    /**
     * @param Request $request
     * @param array $attach
     * @param string $input
     * @param string $folder
     * @return void
     */
    private function handleSingleFile(Request $request, array &$attach, string $input, string $folder)
    {
        [$field, $key] = explode('.', $input);

        if ($request->hasFile($input)) {
            // Delete old if exists
            if (!empty($attach[$field][$key])) {
                Storage::disk('s3')->delete($attach[$field][$key]);
            }

            $attach[$field][$key] = $request->file($input)->store($folder, 's3');
        }
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
