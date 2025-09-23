<?php

namespace App\Models\Forms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TaxReturn;

class Deduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'attach',
        'tax_return_id',
        'car_expenses',
        'travel_expenses',
        'mobile_phone',
        'internet_access',
        'computer',
        'gifts',
        'home_office',
        'books',
        'tax_affairs',
        'uniforms',
        'education',
        'tools',
        'superannuation',
        'office_occupancy',
        'union_fees',
        'sun_protection',
        'low_value_pool',
        'interest_deduction',
        'dividend_deduction',
        'upp',
        'project_pool',
        'investment_scheme',
        'other',
    ];

    protected $casts = [
        'attach' => 'array',
        'car_expenses' => 'array',
        'travel_expenses' => 'array',
        'mobile_phone' => 'array',
        'internet_access' => 'array',
        'computer' => 'array',
        'gifts' => 'array',
        'home_office' => 'array',
        'books' => 'array',
        'tax_affairs' => 'array',
        'uniforms' => 'array',
        'education' => 'array',
        'tools' => 'array',
        'superannuation' => 'array',
        'office_occupancy' => 'array',
        'union_fees' => 'array',
        'sun_protection' => 'array',
        'low_value_pool' => 'array',
        'interest_deduction' => 'array',
        'dividend_deduction' => 'array',
        'upp' => 'array',
        'project_pool' => 'array',
        'investment_scheme' => 'array',
        'other' => 'array',
    ];

    public function taxReturn()
    {
        return $this->belongsTo(TaxReturn::class);
    }
}
