<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class DeductionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'tax_return_id' => $this->tax_return_id,

            // Main deduction sections
            'car_expenses'        => $this->car_expenses,
            'travel_expenses'     => $this->formatSingleFile($this->travel_expenses, 'travel_file'),
            'mobile_phone'        => $this->mobile_phone,
            'internet_access'     => $this->internet_access,
            'computer'            => $this->formatSingleFile($this->computer, 'computer_file'),
            'gifts'               => $this->gifts,
            'home_office'         => $this->formatSingleFile($this->home_office, 'home_receipt'),
            'books'               => $this->formatSingleFile($this->books, 'books_file'),
            'tax_affairs'         => $this->tax_affairs,
            'uniforms'            => $this->formatSingleFile($this->uniforms, 'uniform_receipt'),
            'education'           => $this->formatSingleFile($this->education, 'edu_file'),
            'tools'               => $this->tools,
            'superannuation'      => $this->superannuation,
            'office_occupancy'    => $this->office_occupancy,
            'union_fees'          => $this->formatSingleFile($this->union_fees, 'file'),
            'sun_protection'      => $this->formatSingleFile($this->sun_protection, 'sun_file'),
            'low_value_pool'      => $this->formatMultipleFiles($this->low_value_pool, 'files'),
            'interest_deduction'  => $this->interest_deduction,
            'dividend_deduction'  => $this->dividend_deduction,
            'upp'                 => $this->upp,
            'project_pool'        => $this->project_pool,
            'investment_scheme'   => $this->investment_scheme,
            'other'               => $this->other,

            // Attachments (auto-format)
            'attach' => $this->formatAttachments($this->attach),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Convert a section that contains a single file field (e.g., home_office.home_receipt)
     */
    private function formatSingleFile($section, $key)
    {
        if (empty($section) || !is_array($section)) {
            return $section;
        }

        if (!empty($section[$key])) {
            $section[$key] = $this->toFullUrl($section[$key]);
        }

        return $section;
    }

    /**
     * Convert a section that contains multiple files (e.g., low_value_pool.files)
     */
    private function formatMultipleFiles($section, $key)
    {
        if (empty($section) || !is_array($section)) {
            return $section;
        }

        if (!empty($section[$key]) && is_array($section[$key])) {
            $section[$key] = array_map(fn($file) => $this->toFullUrl($file), $section[$key]);
        }

        return $section;
    }

    /**
     * Recursively format attachments, converting stored file paths to URLs
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
     * Convert file path to full S3/public URL
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
}
