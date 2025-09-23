<?php

namespace App\Models\Forms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TaxReturn;

class BasicInfoForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'attach',
        'tax_return_id',
        'first_name',
        'last_name',
        'day',
        'month',
        'year',
        'phone',
        'gender',
        'has_spouse',
        'future_tax_return',
        'australian_citizenship',
        'visa_type',
        'other_visa_type',
        'long_stay_183',
        'arrival_month',
        'arrival_year',
        'departure_month',
        'departure_year',
        'stay_purpose',
        'full_tax_year',
        'home_address',
        'same_as_home_address',
        'postal_address',
        'has_education_debt',
        'has_sfss_debt',
        'other_tax_debts',
        'occupation',
        'other_occupation'
    ];

    public function taxReturn()
    {
        return $this->belongsTo(TaxReturn::class);
    }
}
