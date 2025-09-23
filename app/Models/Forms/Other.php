<?php

namespace App\Models\Forms;

use App\Models\TaxReturn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Other extends Model
{
    use HasFactory;

    protected $table = 'others';

    /**
     * @var string[]
     */
    protected $fillable = [
        'attach',
        'any_dependent_children',
        'additional_questions',
        'income_tests',
        'mls',
        'tax_return_id',
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

    /**
     * @var string[]
     */
    protected $casts = [
        'attach' => 'array',
        'income_tests' => 'array',
        'mls' => 'array',
        'spouse_details' => 'array',
        'private_health_insurance' => 'array',
        'zone_overseas_forces_offset' => 'array',
        'seniors_offset' => 'array',
        'medicare_reduction_exemption' => 'array',
        'part_year_tax_free_threshold' => 'array',
        'medical_expenses_offset' => 'array',
        'under_18' => 'array',
        'working_holiday_maker_net_income' => 'array',
        'superannuation_income_stream_offset' => 'array',
        'superannuation_contributions_spouse' => 'array',
        'tax_losses_earlier_income_years' => 'array',
        'dependent_invalid_and_carer' => 'array',
        'superannuation_co_contribution' => 'array',
        'other_tax_offsets_refundable' => 'array',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function taxReturn()
    {
        return $this->belongsTo(TaxReturn::class);
    }
}
