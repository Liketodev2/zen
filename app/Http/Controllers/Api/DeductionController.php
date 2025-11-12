<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeductionResource;
use App\Models\Forms\Deduction;
use App\Models\TaxReturn;
use App\Services\DeductionFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeductionController extends Controller
{
    /**
     * @var DeductionFileService
     */
    protected $fileService;

    /**
     * DeductionController constructor.
     *
     * @param DeductionFileService $fileService
     */
    public function __construct(DeductionFileService $fileService)
    {
        $this->fileService = $fileService;
    }


    /**
     * @param Request $request
     * @return DeductionResource|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deduction(Request $request)
    {
        $arrayFields = [
            'car_expenses', 'mobile_phone', 'internet_access','gifts',
            'tax_affairs', 'tools', 'superannuation', 'office_occupancy',
            'interest_deduction', 'dividend_deduction', 'upp','project_pool',
            'investment_scheme', 'other'
        ];

        // Normalize JSON strings to arrays (same pattern you use in IncomeController)
        foreach ($arrayFields as $key) {
            if ($request->has($key) && is_string($request->$key)) {
                $decoded = json_decode($request->$key, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $request->merge([$key => $decoded]);
                }
            }
        }

        $rules = ['tax_id' => 'required|exists:tax_returns,id'];
        foreach ($arrayFields as $field) {
            $rules[$field] = 'nullable|array';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'validation' => true], 422);
        }

        $validated = $validator->validated();

        $taxReturn = TaxReturn::where('id', $validated['tax_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$taxReturn) {
            return response()->json(['error' => 'Tax return not found!', 'validation' => false], 404);
        }

        $deduction = Deduction::firstOrCreate(['tax_return_id' => $taxReturn->id]);

        // attach may be stored as JSON string in DB; decode safely
        $attach = is_string($deduction->attach) ? json_decode($deduction->attach, true) ?: [] : ($deduction->attach ?? []);

        $data = $deduction->toArray();

        // merge arrays: keep existing values if not provided in request
        foreach ($arrayFields as $field) {
            $data[$field] = $validated[$field] ?? ($data[$field] ?? null);
        }

        $deduction->update(array_merge($data, ['attach' => $attach]));

        return (new DeductionResource($deduction->fresh()))
            ->additional([
                'success' => true,
                'message' => 'Deduction data saved successfully!',
            ]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveTravelExpenses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tax_id' => 'required|exists:tax_returns,id',
            'travel_expenses' => 'nullable|array',
            'travel_expenses.travel_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
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

        $deduction = Deduction::firstOrCreate(['tax_return_id' => $taxReturn->id]);

        $attach = is_string($deduction->attach) ? json_decode($deduction->attach, true) ?: [] : ($deduction->attach ?? []);
        $data = $deduction->toArray();

        // Merge existing travel_expenses with incoming
        $existing = $data['travel_expenses'] ?? [];
        $incoming = $request->input('travel_expenses', []);
        $data['travel_expenses'] = array_merge($existing, $incoming);

        // Let the file service handle files for travel expenses
        $this->fileService->handleTravelExpensesFilesForApi($request, $attach, $data);

        $deduction->update([
            'travel_expenses' => $data['travel_expenses'],
            'attach' => $attach,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Travel expenses saved successfully!',
            'data' => new DeductionResource($deduction->fresh()),
        ]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveComputer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tax_id' => 'required|exists:tax_returns,id',
            'computer' => 'nullable|array',
            'computer.computer_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
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

        $deduction = Deduction::firstOrCreate(['tax_return_id' => $taxReturn->id]);
        $attach = is_string($deduction->attach) ? json_decode($deduction->attach, true) ?: [] : ($deduction->attach ?? []);
        $data = $deduction->toArray();

        $existing = $data['computer'] ?? [];
        $incoming = $request->input('computer', []);
        $data['computer'] = array_merge($existing, $incoming);

        $this->fileService->handleComputerFilesForApi($request, $attach, $data);

        $deduction->update([
            'computer' => $data['computer'],
            'attach' => $attach,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Computer data saved successfully!',
            'data' => new DeductionResource($deduction->fresh()),
        ]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveHomeOffice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tax_id' => 'required|exists:tax_returns,id',
            'home_office' => 'nullable|array',
            'home_office.home_receipt' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
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

        $deduction = Deduction::firstOrCreate(['tax_return_id' => $taxReturn->id]);
        $attach = is_string($deduction->attach) ? json_decode($deduction->attach, true) ?: [] : ($deduction->attach ?? []);
        $data = $deduction->toArray();

        $existing = $data['home_office'] ?? [];
        $incoming = $request->input('home_office', []);
        $data['home_office'] = array_merge($existing, $incoming);

        $this->fileService->handleHomeOfficeFilesForApi($request, $attach, $data);

        $deduction->update([
            'home_office' => $data['home_office'],
            'attach' => $attach,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Home office data saved successfully!',
            'data' => new DeductionResource($deduction->fresh()),
        ]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveBooks(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tax_id' => 'required|exists:tax_returns,id',
            'books' => 'nullable|array',
            'books.books_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
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

        $deduction = Deduction::firstOrCreate(['tax_return_id' => $taxReturn->id]);
        $attach = is_string($deduction->attach) ? json_decode($deduction->attach, true) ?: [] : ($deduction->attach ?? []);
        $data = $deduction->toArray();

        $existing = $data['books'] ?? [];
        $incoming = $request->input('books', []);
        $data['books'] = array_merge($existing, $incoming);

        $this->fileService->handleBooksFilesForApi($request, $attach, $data);

        $deduction->update([
            'books' => $data['books'],
            'attach' => $attach,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Books data saved successfully!',
            'data' => new DeductionResource($deduction->fresh()),
        ]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveUniforms(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tax_id' => 'required|exists:tax_returns,id',
            'uniforms' => 'nullable|array',
            'uniforms.uniform_receipt' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
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

        $deduction = Deduction::firstOrCreate(['tax_return_id' => $taxReturn->id]);
        $attach = is_string($deduction->attach) ? json_decode($deduction->attach, true) ?: [] : ($deduction->attach ?? []);
        $data = $deduction->toArray();

        $existing = $data['uniforms'] ?? [];
        $incoming = $request->input('uniforms', []);
        $data['uniforms'] = array_merge($existing, $incoming);

        $this->fileService->handleUniformsFiles($request, $attach, $data);

        $deduction->update([
            'uniforms' => $data['uniforms'],
            'attach' => $attach,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Uniforms data saved successfully!',
            'data' => new DeductionResource($deduction->fresh()),
        ]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveEducation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tax_id' => 'required|exists:tax_returns,id',
            'education' => 'nullable|array',
            'education.edu_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
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

        $deduction = Deduction::firstOrCreate(['tax_return_id' => $taxReturn->id]);
        $attach = is_string($deduction->attach) ? json_decode($deduction->attach, true) ?: [] : ($deduction->attach ?? []);
        $data = $deduction->toArray();

        $existing = $data['education'] ?? [];
        $incoming = $request->input('education', []);
        $data['education'] = array_merge($existing, $incoming);

        $this->fileService->handleEducationFilesForApi($request, $attach, $data);

        $deduction->update([
            'education' => $data['education'],
            'attach' => $attach,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Education data saved successfully!',
            'data' => new DeductionResource($deduction->fresh()),
        ]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveUnionFees(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tax_id' => 'required|exists:tax_returns,id',
            'union_fees' => 'nullable|array',
            'union_fees.file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
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

        $deduction = Deduction::firstOrCreate(['tax_return_id' => $taxReturn->id]);
        $attach = is_string($deduction->attach) ? json_decode($deduction->attach, true) ?: [] : ($deduction->attach ?? []);
        $data = $deduction->toArray();

        $existing = $data['union_fees'] ?? [];
        $incoming = $request->input('union_fees', []);
        $data['union_fees'] = array_merge($existing, $incoming);

        $this->fileService->handleUnionFeesFilesForApi($request, $attach, $data);

        $deduction->update([
            'union_fees' => $data['union_fees'],
            'attach' => $attach,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Union fees saved successfully!',
            'data' => new DeductionResource($deduction->fresh()),
        ]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveSunProtection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tax_id' => 'required|exists:tax_returns,id',
            'sun_protection' => 'nullable|array',
            'sun_protection.sun_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
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

        $deduction = Deduction::firstOrCreate(['tax_return_id' => $taxReturn->id]);
        $attach = is_string($deduction->attach) ? json_decode($deduction->attach, true) ?: [] : ($deduction->attach ?? []);
        $data = $deduction->toArray();

        $existing = $data['sun_protection'] ?? [];
        $incoming = $request->input('sun_protection', []);
        $data['sun_protection'] = array_merge($existing, $incoming);

        $this->fileService->handleSunProtectionFilesForApi($request, $attach, $data);

        $deduction->update([
            'sun_protection' => $data['sun_protection'],
            'attach' => $attach,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sun protection saved successfully!',
            'data' => new DeductionResource($deduction->fresh()),
        ]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveLowValuePool(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tax_id' => 'required|exists:tax_returns,id',
            'low_value_pool' => 'nullable|array',
            'low_value_pool.files.*' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
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

        $deduction = Deduction::firstOrCreate(['tax_return_id' => $taxReturn->id]);
        $attach = is_string($deduction->attach) ? json_decode($deduction->attach, true) ?: [] : ($deduction->attach ?? []);
        $data = $deduction->toArray();

        $existing = $data['low_value_pool'] ?? [];
        $incoming = $request->input('low_value_pool', []);
        $data['low_value_pool'] = array_merge($existing, $incoming);

        $this->fileService->handleLowValuePoolFilesForApi($request, $attach, $data);

        $deduction->update([
            'low_value_pool' => $data['low_value_pool'],
            'attach' => $attach,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Low value pool saved successfully!',
            'data' => new DeductionResource($deduction->fresh()),
        ]);
    }
}
