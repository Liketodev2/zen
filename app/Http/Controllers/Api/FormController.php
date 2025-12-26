<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BasicInfoResource;
use App\Http\Resources\DeductionResource;
use App\Http\Resources\IncomeResource;
use App\Http\Resources\OtherResource;
use App\Models\Forms\BasicInfoForm;
use App\Models\Forms\Deduction;
use App\Models\Forms\Income;
use App\Models\Forms\Other;
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
            return response()->json(['errors' => $validator->errors(), 'validation' => true], 422);
        }

        $validated = $validator->validated();

        $taxReturn = TaxReturn::where('id', $validated['tax_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$taxReturn) {
            return response()->json([
                'validation' => false,
                'error' => 'Tax Return not found or unauthorized',
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



    public function income(Request $request)
    {
        $arrayFields = [
            'salary', 'interests', 'dividends', 'government_allowances',
            'government_pensions', 'capital_gains', 'managed_funds',
            'termination_payments', 'rent', 'partnerships', 'annuities',
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



        // 🧾 Validation rules
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

            // 🗂 File rules
            'capital_gains.cgt_attachment'      => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'managed_fund_files.*'              => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'termination_payments.*.etp_files.*'=> 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'rent.*.rent_files.*'               => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ];




        // 🧰 Validate input
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        // 🧾 Check tax return ownership
        $taxReturn = TaxReturn::where('id', $validated['tax_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$taxReturn) {
            return response()->json([
                'success' => false,
                'message' => 'Tax Return not found or unauthorized',
            ], 404);
        }

        // 🔍 Fetch existing income data if available
        $existing = Income::where('tax_return_id', $taxReturn->id)->first();

        // 🔄 Merge with existing values
        $fields = array_keys($rules);
        $data   = [];
        foreach ($fields as $field) {
            if ($field === 'tax_id') continue;
            $data[$field] = $validated[$field] ?? ($existing->$field ?? null);
        }

        // 🗃 Initialize attachments
        $attach = $existing ? ($existing->attach ?? []) : [];

        // 📁 Handle all file uploads
        $this->fileService->handleCapitalGainsFiles($request, $attach, $data);
        $this->fileService->handleManagedFundsFiles($request, $attach, $data);
        $this->fileService->handleTerminationPaymentsFiles($request, $attach, $data);
        $this->fileService->handleRentFiles($request, $attach, $data);

        // 🔒 Save updated attachment data
        $data['attach'] = $attach;

        // ✅ Explicitly update if provided
        foreach (['other_income', 'salary', 'interests', 'government_pensions'] as $key) {
            if ($request->has($key)) {
                $data[$key] = $request->input($key);
            }
        }

        // 💾 Create or update
        if ($existing) {
            $existing->update($data);
            $income = $existing->fresh();
            $message = 'Income data updated successfully!';
        } else {
            $income = Income::create(array_merge($data, [
                'tax_return_id' => $taxReturn->id,
            ]));
            $message = 'Income data saved successfully!';
        }

        // 📤 Return API response
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
            'home_office.home_receipt_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'books.books_file'    => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'uniforms.uniform_receipt' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'education.edu_file'  => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'union_fees.file'     => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'sun_protection.sun_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'low_value_pool.files.*' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'validation' => true], 422);
        }

        $validated = $validator->validated();

        $taxReturn = TaxReturn::where('id', $validated['tax_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$taxReturn) {
            return response()->json([
                'validation' => false,
                'error' => 'Tax Return not found or unauthorized',
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
            'home_office.home_receipt_file' => 'home_office',
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


    /**
     * Store or update "Other" section data for a Tax Return.
     *
     * @param Request $request
     * @return OtherResource|\Illuminate\Http\JsonResponse
     */
    public function other(Request $request)
    {
        $rules = [
            'tax_id'                               => 'required|exists:tax_returns,id',
            'any_dependent_children'               => 'nullable|string|max:255',
            'additional_questions'                 => 'nullable|string|max:255',
            'income_tests'                         => 'nullable|array',
            'mls'                                  => 'nullable|array',
            'spouse_details'                       => 'nullable|array',
            'private_health_insurance'             => 'nullable|array',
            'zone_overseas_forces_offset'          => 'nullable|array',
            'seniors_offset'                       => 'nullable|array',
            'medicare_reduction_exemption'         => 'nullable|array',
            'part_year_tax_free_threshold'         => 'nullable|array',
            'medical_expenses_offset'              => 'nullable|array',
            'under_18'                             => 'nullable|array',
            'working_holiday_maker_net_income'     => 'nullable|array',
            'superannuation_income_stream_offset'  => 'nullable|array',
            'superannuation_contributions_spouse'  => 'nullable|array',
            'tax_losses_earlier_income_years'      => 'nullable|array',
            'dependent_invalid_and_carer'          => 'nullable|array',
            'superannuation_co_contribution'       => 'nullable|array',
            'other_tax_offsets_refundable'         => 'nullable|array',

            // Files validation with max size 5MB
            'additional_file.*'                    => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'private_health_insurance.*.*'         => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'medicare_certificate'                 => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'medical_expense_file'                 => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'validation' => true], 422);
        }

        $validated = $validator->validated();

        $taxReturn = TaxReturn::where('id', $validated['tax_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$taxReturn) {
            return response()->json([
                'validation' => false,
                'error' => 'Tax Return not found or unauthorized',
            ], 404);
        }

        $existing = Other::where('tax_return_id', $taxReturn->id)->first();

        $data = collect($validated)->except(['tax_id'])->toArray();

        $attach = $existing ? ($existing->attach ?? []) : [];

        if ($request->hasFile('additional_file')) {
            $attach['additional_file'] = [];
            foreach ($request->file('additional_file') as $file) {
                $path = $file->store('attachments', 's3');
                $attach['additional_file'][] = Storage::disk('s3')->url($path);
            }
        }

        if ($request->hasFile('private_health_insurance')) {
            foreach ($request->file('private_health_insurance') as $index => $fileGroup) {
                foreach ($fileGroup as $key => $file) {
                    $path = $file->store('attachments', 's3');
                    $attach['private_health_insurance'][$index][$key] = Storage::disk('s3')->url($path);
                }
            }
        }

        if ($request->hasFile('medicare_certificate')) {
            $path = $request->file('medicare_certificate')->store('attachments', 's3');
            $attach['medicare_certificate'] = Storage::disk('s3')->url($path);
        }

        if ($request->hasFile('medical_expense_file')) {
            $path = $request->file('medical_expense_file')->store('attachments', 's3');
            $attach['medical_expense_file'] = Storage::disk('s3')->url($path);
        }

        $data['attach'] = $attach;


        if ($existing) {
            $existing->update($data);
            $other = $existing->fresh();
            $message = 'Other data updated successfully!';
        } else {
            $other = Other::create(array_merge($data, [
                'tax_return_id' => $taxReturn->id,
            ]));
            $message = 'Other data saved successfully!';
        }

        return (new OtherResource($other))->additional([
            'success' => true,
            'message' => $message,
        ]);
    }


}
