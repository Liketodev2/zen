<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class IncomeResource extends JsonResource
{

    /**
     * @param $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'tax_return_id' => $this->tax_return_id,
            'salary' => $this->salary,
            'interests' => $this->interests,
            'dividends' => $this->dividends,
            'government_allowances' => $this->government_allowances,
            'government_pensions' => $this->government_pensions,
            'capital_gains' => $this->formatCapitalGains($this->capital_gains),
            'managed_funds' => $this->formatManagedFunds($this->managed_funds),
            'termination_payments' => $this->termination_payments,
            'rent' => $this->formatRent($this->rent),
            'partnerships' => $this->partnerships,
            'annuities' => $this->annuities,
            'superannuation' => $this->superannuation,
            'super_lump_sums' => $this->super_lump_sums,
            'ess' => $this->ess,
            'personal_services' => $this->personal_services,
            'business_income' => $this->business_income,
            'business_losses' => $this->business_losses,
            'foreign_income' => $this->foreign_income,
            'other_income' => $this->other_income,
            'attach' => $this->formatAttachments($this->attach),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }


    /**
     * @param $managedFunds
     * @return array
     */
    private function formatManagedFunds($managedFunds)
    {
        if (!$managedFunds || !is_array($managedFunds)) return [];

        $result = [];

        // Keep 'info' as is
        $result['info'] = $managedFunds['info'] ?? [];

        // Convert top-level managed_fund_files to full URLs
        if (!empty($managedFunds['managed_fund_files']) && is_array($managedFunds['managed_fund_files'])) {
            $result['managed_fund_files'] = array_map(
                fn($file) => $this->toFullUrl($file),
                $managedFunds['managed_fund_files']
            );
        } else {
            $result['managed_fund_files'] = [];
        }

        return $result;
    }


    /**
     * @param $attachments
     * @return array|array[]|\array[][]|null[]|\null[][]|\null[][][]|string|string[]|\string[][]|\string[][][]|null
     */
    private function formatAttachments($attachments)
    {
        if (is_array($attachments)) {
            return array_map(function ($item) {
                if (is_array($item)) {
                    return $this->formatAttachments($item);
                }
                return $this->toFullUrl($item);
            }, $attachments);
        }
        return $attachments ? $this->toFullUrl($attachments) : null;
    }


    /**
     * @param $capitalGains
     * @return null
     */
    private function formatCapitalGains($capitalGains)
    {
        if (!$capitalGains) return null;

        if (!empty($capitalGains['cgt_attachment'])) {
            $capitalGains['cgt_attachment'] = $this->toFullUrl($capitalGains['cgt_attachment']);
        }

        return $capitalGains;
    }


    /**
     * @param $rent
     * @return array
     */
    private function formatRent($rent)
    {
        if (!$rent || !is_array($rent)) return [];

        foreach ($rent as $index => $r) {
            if (!empty($r['rent_files']) && is_array($r['rent_files'])) {
                $rent[$index]['rent_files'] = array_map(
                    fn($file) => $this->toFullUrl($file),
                    $r['rent_files']
                );
            }
        }

        return $rent;
    }


    /**
     * @param $path
     * @return string|null
     */
    private function toFullUrl($path)
    {
        if (!$path) return null;

        // If already full URL (starts with http), return as is
        if (str_starts_with($path, 'http')) return $path;

        // Otherwise, generate full URL from Storage
        return Storage::url($path);
    }
}
