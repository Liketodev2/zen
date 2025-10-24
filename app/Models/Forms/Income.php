<?php

namespace App\Models\Forms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TaxReturn;

class Income extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'tax_return_id',
        'salary',
        'interests',
        'dividends',
        'government_allowances',
        'government_pensions',
        'capital_gains',
        'managed_funds',
        'termination_payments',
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
        'attach',
    ];


    /**
     * @var string[]
     */
    protected $casts = [
        'salary'                => 'array',
        'interests'             => 'array',
        'dividends'             => 'array',
        'government_allowances' => 'array',
        'government_pensions'   => 'array',
        'capital_gains'         => 'array',
        'managed_funds'         => 'array',
        'termination_payments'  => 'array',
        'rent'                  => 'array',
        'partnerships'          => 'array',
        'annuities'             => 'array',
        'superannuation'        => 'array',
        'super_lump_sums'       => 'array',
        'ess'                   => 'array',
        'personal_services'     => 'array',
        'business_income'       => 'array',
        'business_losses'       => 'array',
        'foreign_income'        => 'array',
        'other_income'          => 'array',
        'attach'                => 'array',
    ];


    public function taxReturn()
    {
        return $this->belongsTo(TaxReturn::class);
    }
}
