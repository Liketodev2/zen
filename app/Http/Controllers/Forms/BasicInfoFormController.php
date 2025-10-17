<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use App\Models\Forms\BasicInfoForm;
use App\Models\TaxReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class  BasicInfoFormController extends Controller
{


    /**
     * @param Request $request
     * @param $taxId
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    private function saveBasicInfo(Request $request, $taxId, $id = null)
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
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();


        $validated['tax_return_id'] = $taxReturn->id;

        if ($id) {
            $basicInfo = BasicInfoForm::findOrFail($id);

            if ($basicInfo->taxReturn->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $basicInfo->update($validated);
            $message = 'Form updated successfully!';
        } else {
            $basicInfo = BasicInfoForm::create($validated);
            $message = 'Form saved successfully!';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'basicInfoId' => $basicInfo->id
        ]);
    }


    /**
     * @param Request $request
     * @param string $taxId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, string $taxId)
    {
        return $this->saveBasicInfo($request, $taxId);
    }


    /**
     * @param Request $request
     * @param string $
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $taxId, string $id)
    {
        return $this->saveBasicInfo($request, $taxId, $id);
    }

}
