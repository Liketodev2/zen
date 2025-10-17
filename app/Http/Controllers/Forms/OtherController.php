<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use App\Models\Forms\Other;
use Illuminate\Http\Request;
use App\Models\TaxReturn;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class OtherController extends Controller
{

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

        $rules = [
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
            'additional_file.*'                     => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'private_health_insurance.*.*'         => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'medicare_certificate'                 => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'medical_expense_file'                 => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = [
            'zone_overseas_forces_offset' => $request->input('zone_overseas_forces_offset', null),
            'any_dependent_children' => $request->input('any_dependent_children', null),
            'additional_questions' => $request->input('additional_questions', null),
            'income_tests' => $request->input('income_tests',  null),
            'mls' => $request->input('mls', null),
            'seniors_offset' => $request->input('seniors_offset', null),
            'spouse_details' => $request->input('spouse_details', null),
            'private_health_insurance' => $request->input('private_health_insurance', null),
            'part_year_tax_free_threshold' => $request->input('part_year_tax_free_threshold', null),
            'under_18' => $request->input('under_18', null),
            'working_holiday_maker_net_income' => $request->input('working_holiday_maker_net_income', null),
            'superannuation_income_stream_offset' => $request->input('superannuation_income_stream_offset', null),
            'superannuation_contributions_spouse' => $request->input('superannuation_contributions_spouse', null),
            'tax_losses_earlier_income_years' => $request->input('tax_losses_earlier_income_years', null),
            'dependent_invalid_and_carer' => $request->input('dependent_invalid_and_carer', null),
            'superannuation_co_contribution' => $request->input('superannuation_co_contribution', null),
            'other_tax_offsets_refundable' => $request->input('other_tax_offsets_refundable', null),
            'medicare_reduction_exemption' => $request->input('medicare_reduction_exemption', null),
            'medical_expenses_offset' => $request->input('medical_expenses_offset', null),
        ];



        // Handle "attach" section (text + files)
        $attach = [];

        // Keep existing attach data if updating
        if ($id) {
            $existing = Other::findOrFail($id);
            $attach = $existing->attach ?? [];
        }


        // Handle uploaded files
        if ($request->hasFile('additional_file')) {
            // Delete old files from storage if they exist
            if (!empty($attach['additional_file'])) {
                foreach ($attach['additional_file'] as $oldFile) {
                    if (Storage::disk('s3')->exists($oldFile)) {
                        Storage::disk('s3')->delete($oldFile);
                    }
                }
            }

            // Replace with new files
            $attach['additional_file'] = [];
            foreach ($request->file('additional_file') as $file) {
                $attach['additional_file'][] =  $file->store('attachments', 's3');
            }
        }

        if ($request->has('private_health_insurance')) {
            $phiFiles = $request->file('private_health_insurance', []);
            foreach ($phiFiles as $key => $file) {
                if ($file) {
                    if (!empty($attach['private_health_insurance'][$key])) {
                        $oldFile = $attach['private_health_insurance'][$key];
                        if (Storage::disk('s3')->exists($oldFile)) {
                            Storage::disk('s3')->delete($oldFile);
                        }
                    }
                    $attach['private_health_insurance'][$key] = $file->store('attachments', 's3');
                }
            }
        }

        if ($request->hasFile('medicare_certificate')) {
            if (!empty($attach['medicare_certificate'])) {
                if (Storage::disk('s3')->exists($attach['medicare_certificate'])) {
                    Storage::disk('s3')->delete($attach['medicare_certificate']);
                }
            }
            $attach['medicare_certificate'] = $request->file('medicare_certificate')->store('attachments', 's3');
        }


        if ($request->hasFile('medical_expense_file')) {
            if (!empty($attach['medical_expense_file'])) {
                if (Storage::disk('s3')->exists($attach['medical_expense_file'])) {
                    Storage::disk('s3')->delete($attach['medical_expense_file']);
                }
            }
            $attach['medical_expense_file'] = $request->file('medical_expense_file')->store('attachments', 's3');
        }

        $data['attach'] = $attach;


        if ($id) {
            $other = Other::findOrFail($id);
            $other->update($data);
            $message = 'Other data updated successfully!';
        } else {
            $other = Other::create(array_merge($data, [
                'tax_return_id' => $taxReturn->id
            ]));
            $message = 'Other data saved successfully!';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
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
}
