<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BasicInfoResource;
use App\Http\Resources\DeductionResource;
use App\Http\Resources\IncomeResource;
use App\Models\Forms\BasicInfoForm;
use App\Models\Forms\Deduction;
use App\Models\Forms\Income;
use App\Models\TaxReturn;
use App\Services\IncomeFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
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
     * @return BasicInfoResource|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
//    public function basicInfo(Request $request)
//    {
//        $rules = [
//            'tax_id' => 'required|exists:tax_returns,id',
//            'first_name' => 'nullable|string|max:255',
//            'last_name' => 'nullable|string|max:255',
//            'day' => 'nullable|integer|min:1|max:31',
//            'month' => 'nullable|string|min:1|max:12',
//            'year' => 'nullable|string',
//            'phone' => 'nullable|string|max:20',
//            'gender' => 'nullable|in:male,female',
//            'has_spouse' => 'nullable|in:yes,no',
//            'future_tax_return' => 'nullable|in:yes,no',
//            'australian_citizenship' => 'nullable|in:yes,no',
//            'visa_type' => 'nullable|string',
//            'other_visa_type' => 'nullable|string',
//            'long_stay_183' => 'nullable|in:yes,no',
//            'arrival_month' => 'nullable|string|min:1|max:12',
//            'arrival_year' => 'nullable|string',
//            'departure_month' => 'nullable|string|min:1|max:12',
//            'departure_year' => 'nullable|string',
//            'stay_purpose' => 'nullable|string',
//            'full_tax_year' => 'nullable|in:yes,no',
//            'home_address' => 'nullable|string',
//            'same_as_home_address' => 'nullable|in:yes,no',
//            'postal_address' => 'nullable|string',
//            'has_education_debt' => 'nullable|in:yes,no',
//            'has_sfss_debt' => 'nullable|in:yes,no',
//            'other_tax_debts' => 'nullable|string',
//            'occupation' => 'nullable|string',
//            'other_occupation' => 'nullable|string',
//        ];
//
//
//        $validator = Validator::make($request->all(), $rules);
//
//        if ($validator->fails()) {
//            return response()->json([
//                'success' => false,
//                'message' => 'Validation errors',
//                'errors' => $validator->errors(),
//            ], 422);
//        }
//
//        $validated = $validator->validated();
//
//
//        $taxReturn = TaxReturn::where('id', $validated['tax_id'])
//            ->where('user_id', $request->user()->id)
//            ->first();
//
//        if (!$taxReturn) {
//            return response()->json([
//                'success' => false,
//                'message' => 'Tax Return not found or unauthorized',
//            ], 404);
//        }
//
//        $booleanFields = [
//            'has_spouse',
//            'future_tax_return',
//            'australian_citizenship',
//            'long_stay_183',
//            'full_tax_year',
//            'same_as_home_address',
//            'has_education_debt',
//            'has_sfss_debt',
//        ];
//
//        foreach ($booleanFields as $field) {
//            if (isset($validated[$field])) {
//                $validated[$field] = $validated[$field] === 'yes' ? 1 : 0;
//            }
//        }
//
//        $validated['tax_return_id'] = $taxReturn->id;
//
//        $basicInfo = BasicInfoForm::firstOrNew([
//            'tax_return_id' => $taxReturn->id,
//        ]);
//
//        $basicInfo->fill($validated);
//        $basicInfo->save();
//
//        $message = $basicInfo->wasRecentlyCreated
//            ? 'Form saved successfully!'
//            : 'Form updated successfully!';
//
//        return (new BasicInfoResource($basicInfo))
//            ->additional([
//                'success' => true,
//                'message' => $message,
//            ]);
//    }


    public function basicInfo(Request $request)
    {
        $rules = [
            'tax_id' => 'required|exists:tax_returns,id',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'day' => 'nullable|string|min:1|max:31',
            'month' => 'nullable|string|min:1|max:12',
            'year' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female',
            'has_spouse' => 'nullable|boolean',
            'future_tax_return' => 'nullable|boolean',
            'australian_citizenship' => 'nullable|boolean',
            'visa_type' => 'nullable|string',
            'other_visa_type' => 'nullable|string',
            'long_stay_183' => 'nullable|boolean',
            'arrival_month' => 'nullable|string|min:1|max:12',
            'arrival_year' => 'nullable|string',
            'departure_month' => 'nullable|string|min:1|max:12',
            'departure_year' => 'nullable|string',
            'stay_purpose' => 'nullable|string',
            'full_tax_year' => 'nullable|boolean',
            'home_address' => 'nullable|string',
            'same_as_home_address' => 'nullable|boolean',
            'postal_address' => 'nullable|string',
            'has_education_debt' => 'nullable|boolean',
            'has_sfss_debt' => 'nullable|boolean',
            'other_tax_debts' => 'nullable|string',
            'occupation' => 'nullable|string',
            'other_occupation' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $taxReturn = TaxReturn::where('id', $validated['tax_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$taxReturn) {
            return response()->json([
                'success' => false,
                'message' => 'Tax Return not found or unauthorized',
            ], 404);
        }

        $validated['tax_return_id'] = $taxReturn->id;

        $basicInfo = BasicInfoForm::firstOrNew([
            'tax_return_id' => $taxReturn->id,
        ]);

        $basicInfo->fill($validated);
        $basicInfo->save();

        $message = $basicInfo->wasRecentlyCreated
            ? 'Form saved successfully!'
            : 'Form updated successfully!';

        return (new BasicInfoResource($basicInfo))
            ->additional([
                'success' => true,
                'message' => $message,
            ]);
    }


    /**
     * @param Request $request
     * @return IncomeResource|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function income(Request $request)
    {
        $rules = [
            'tax_id' => 'required|exists:tax_returns,id',
            'salary'                => 'nullable|array',
            'interests'             => 'nullable|array',
            'dividends'             => 'nullable|array',
            'government_allowances' => 'nullable|array',
            'government_pensions'   => 'nullable|array',
            'capital_gains'         => 'nullable|array',
            'managed_funds'         => 'nullable|array',
            'termination_payments'  => 'nullable|array',
            'rent'                  => 'nullable|array',
            'partnerships'          => 'nullable|array',
            'annuities'             => 'nullable|array',
            'superannuation'        => 'nullable|array',
            'super_lump_sums'       => 'nullable|array',
            'ess'                   => 'nullable|array',
            'personal_services'     => 'nullable|array',
            'business_income'       => 'nullable|array',
            'business_losses'       => 'nullable|array',
            'foreign_income'        => 'nullable|array',
            'other_income'          => 'nullable|array',
            'capital_gains.cgt_attachment' => 'nullable|file|mimes:pdf,jpg,png|max:5120', // 5MB
            'managed_fund_files.*'  => 'nullable|file|mimes:pdf,jpg,png|max:5120', // 5MB
            'termination_payments.*.etp_files.*' => 'nullable|file|mimes:pdf,jpg,png|max:5120', // 5MB
            'rent.*.rent_files.*'   => 'nullable|file|mimes:pdf,jpg,png|max:5120', // 5MB
        ];


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();


        $taxReturn = TaxReturn::where('id', $validated['tax_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$taxReturn) {
            return response()->json([
                'success' => false,
                'message' => 'Tax Return not found or unauthorized',
            ], 404);
        }


        // Check if an Income record already exists for this tax return
        $existing = Income::where('tax_return_id', $taxReturn->id)->first();

        // Merge request data with existing data
        $fields = array_keys($rules);
        $data   = [];
        foreach ($fields as $field) {
            if ($field === 'tax_id') continue;
            $data[$field] = $validated[$field] ?? ($existing->$field ?? null);
        }

        // Handle file uploads
        $attach = $existing ? ($existing->attach ?? []) : [];
        $attach = $this->fileService->handleAllFiles($request, $attach);
        $data['attach'] = $attach;

        // Create or update record
        if ($existing) {
            $existing->update($data);
            $income  = $existing->fresh(); // get updated record
            $message = 'Income data updated successfully!';
        } else {
            $income = Income::create(array_merge($data, [
                'tax_return_id' => $taxReturn->id,
            ]));
            $message = 'Income data saved successfully!';
        }

        return (new IncomeResource($income))
            ->additional([
                'success' => true,
                'message' => $message,
            ]);
    }


    /**
     * @param Request $request
     * @return DeductionResource|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deduction(Request $request)
    {
        $rules = [
            'tax_id' => 'required|exists:tax_returns,id',
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

            'travel_expenses.travel_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'computer.computer_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'home_office.home_receipt' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'books.books_file'    => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'uniforms.uniform_receipt' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'education.edu_file'  => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'union_fees.file'     => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'sun_protection.sun_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'low_value_pool.files.*' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $taxReturn = TaxReturn::where('id', $validated['tax_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$taxReturn) {
            return response()->json([
                'success' => false,
                'message' => 'Tax Return not found or unauthorized',
            ], 404);
        }

        // Check if deduction already exists
        $existing = Deduction::where('tax_return_id', $taxReturn->id)->first();

        $fields = array_keys($rules);
        $data = [];

        foreach ($fields as $field) {
            if ($field === 'tax_id') continue;
            $data[$field] = $validated[$field] ?? ($existing->$field ?? null);
        }

        // Handle file uploads (similar to Income)
        $attach = $existing ? ($existing->attach ?? []) : [];

        // Handle all single file fields
        $fileFields = [
            'computer.computer_file' => 'computer',
            'travel_expenses.travel_file' => 'travel_expenses',
            'union_fees.file' => 'union_fees',
            'sun_protection.sun_file' => 'sun_protection',
            'education.edu_file' => 'education',
            'uniforms.uniform_receipt' => 'uniforms',
            'books.books_file' => 'books',
            'home_office.home_receipt' => 'home_office',
        ];

        foreach ($fileFields as $input => $folder) {
            [$field, $key] = explode('.', $input);
            if ($request->hasFile($input)) {
                if (!empty($attach[$field][$key])) {
                    Storage::disk('s3')->delete($attach[$field][$key]);
                }
                $attach[$field][$key] = $request->file($input)->store($folder, 's3');
            }
        }

        // Handle low_value_pool multiple files
        if ($request->hasFile('low_value_pool.files')) {
            if (!empty($attach['low_value_pool']['files'])) {
                foreach ($attach['low_value_pool']['files'] as $oldFile) {
                    Storage::disk('s3')->delete($oldFile);
                }
            }
            $paths = [];
            foreach ($request->file('low_value_pool.files') as $file) {
                $paths[] = $file->store('low_value_pool', 's3');
            }
            $attach['low_value_pool']['files'] = $paths;
        }

        $data['attach'] = $attach;

        // Create or update record
        if ($existing) {
            $existing->update($data);
            $deduction = $existing->fresh();
            $message = 'Deduction data updated successfully!';
        } else {
            $deduction = Deduction::create(array_merge($data, [
                'tax_return_id' => $taxReturn->id,
            ]));
            $message = 'Deduction data saved successfully!';
        }

        return (new DeductionResource($deduction))
            ->additional([
                'success' => true,
                'message' => $message,
            ]);
    }

}
