<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class DeductionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'tax_return_id' => $this->tax_return_id,

            // Deduction sections (uniform logic)
            'car_expenses'       => $this->car_expenses,
            'travel_expenses'    => $this->formatSection($this->travel_expenses, 'travel_file'),
            'mobile_phone'       => $this->mobile_phone,
            'internet_access'    => $this->internet_access,
            'computer'           => $this->formatSection($this->computer, 'computer_file'),
            'gifts'              => $this->gifts,
            'home_office' => $this->formatSectionWithFiles(
                $this->home_office,
                [
                    'home_receipt_file',
                    'hours_worked_record_file_yes',
                ]
            ),
            'books'              => $this->formatSection($this->books, 'books_file'),
            'tax_affairs'        => $this->tax_affairs,
            'uniforms'           => $this->formatSection($this->uniforms, 'uniform_receipt'),
            'education'          => $this->formatSection($this->education, 'edu_file'),
            'tools'              => $this->tools,
            'superannuation'     => $this->superannuation,
            'office_occupancy'   => $this->office_occupancy,
            'union_fees'         => $this->formatSection($this->union_fees, 'file'),
            'sun_protection'     => $this->formatSection($this->sun_protection, 'sun_file'),

            // Section containing multiple files
            'low_value_pool'     => $this->formatMultiFileSection($this->low_value_pool, 'files'),

            'interest_deduction' => $this->interest_deduction,
            'dividend_deduction' => $this->dividend_deduction,
            'upp'                => $this->upp,
            'project_pool'       => $this->project_pool,
            'investment_scheme'  => $this->investment_scheme,
            'other'              => $this->other,

            // Attachments, recursive like IncomeResource
            'attach' => $this->formatAttachments($this->attach),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Handle sections with a single file field (matches IncomeResource style)
     */
    private function formatSection($section, $fileKey)
    {
        if (empty($section) || !is_array($section)) {
            return null;
        }

        $hasData = false;

        // Convert file to URL if present
        if (!empty($section[$fileKey])) {
            $section[$fileKey] = $this->toFullUrl($section[$fileKey]);
            $hasData = true;
        }

        // If other values contain data
        if (!$hasData && !empty(array_filter($section))) {
            $hasData = true;
        }

        return $hasData ? $section : null;
    }

    /**
     * Handle sections with multiple files (same logic as IncomeResource rent/terminationPayments)
     */
    private function formatMultiFileSection($section, $fileKey)
    {
        if (empty($section) || !is_array($section)) {
            return null;
        }

        $hasData = false;

        if (!empty($section[$fileKey]) && is_array($section[$fileKey])) {
            $section[$fileKey] = array_map(fn($file) => $this->toFullUrl($file), $section[$fileKey]);
            $hasData = true;
        }

        // Check for any non-empty value
        if (!$hasData && !empty(array_filter($section))) {
            $hasData = true;
        }

        return $hasData ? $section : null;
    }

    /**
     * Recursive attachment formatter (same as IncomeResource)
     */
    private function formatAttachments($attachments)
    {
        if (is_array($attachments)) {
            return array_map(function ($item) {
                return is_array($item)
                    ? $this->formatAttachments($item)
                    : $this->toFullUrl($item);
            }, $attachments);
        }

        return $attachments ? $this->toFullUrl($attachments) : null;
    }

    /**
     * Convert file path to full URL
     */
    private function toFullUrl($path)
    {
        if (!$path) {
            return null;
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return Storage::url($path);
    }


    /**
     * @param $section
     * @param array $fileKeys
     * @return array|null
     */
    private function formatSectionWithFiles($section, array $fileKeys)
    {
        if (empty($section) || !is_array($section)) {
            return null;
        }

        $hasData = false;

        foreach ($fileKeys as $fileKey) {
            if (!empty($section[$fileKey])) {
                $section[$fileKey] = $this->toFullUrl($section[$fileKey]);
                $hasData = true;
            }
        }

        // Check for any other filled data
        if (!$hasData && !empty(array_filter($section))) {
            $hasData = true;
        }

        return $hasData ? $section : null;
    }

}
