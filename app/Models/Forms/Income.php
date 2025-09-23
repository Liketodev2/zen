<?php

namespace App\Models\Forms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TaxReturn;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'attach',
        'managed_funds',
        'termination_payments',
        'salary',
        'interests',
        'dividends',
        'government_allowances',
        'government_pensions',
        'capital_gains',
        'rent',
        'partnerships',
        'annuities',
        'superannuation',
        'super_lump_sums',
        'ess',
        'personal_services',
        'business_income',
        'business_losses',
        'foreign_income',
        'other_income',
        'tax_return_id',
    ];

    protected $casts = [
        'attach' => 'array',
        'salary' => 'array', // Salary / Wages
        'interests' => 'array', // Interest
        'dividends' => 'array', // Dividends
        'government_allowances' => 'array', // Government Allowances
        'government_pensions' => 'array', // Government Pension
        'capital_gains' => 'array', // Capital Gains or Losses
        'managed_funds' => 'array', // Managed Funds
        'termination_payments' => 'array', // Termination Payments
        'rent' => 'array', // Rent Received
        'partnerships' => 'array', // Partnerships and Trusts
        'annuities' => 'array', // Australian Annuities
        'superannuation' => 'array', // Superannuation Income Stream
        'super_lump_sums' => 'array', // Super Lump Sums
        'ess' => 'array', // Employee Share Schemes
        'personal_services' => 'array', // Personal Services Income
        'business_income' => 'array', // Income / Loss From Business
        'business_losses' => 'array', // Deferred Business Losses
        'foreign_income' => 'array', // Foreign Source Income
        'other_income' => 'array', // Other Income
    ];

    public function taxReturn()
    {
        return $this->belongsTo(TaxReturn::class);
    }
}
