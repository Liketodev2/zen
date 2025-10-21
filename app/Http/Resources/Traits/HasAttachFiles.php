<?php

namespace App\Http\Resources\Traits;

use Illuminate\Support\Facades\Storage;

trait HasAttachFiles
{
    /**
     * Merge "attach" file data into the main resource array dynamically.
     */
    protected function mergeAttachFiles(array $data, ?array $attach): array
    {
        if (empty($attach)) {
            return $data;
        }

        foreach ($attach as $section => $filesOrGroups) {
            if (is_array($filesOrGroups)) {
                $data[$section] = $this->mergeFilesRecursively(
                    $data[$section] ?? [],
                    $filesOrGroups
                );
            }
        }

        unset($data['attach']); // clean up

        return $data;
    }

    /**
     * Recursively merge new file data into existing resource data.
     * Converts S3 paths → full URLs.
     */
    protected function mergeFilesRecursively($existing, $new)
    {
        foreach ($new as $key => $value) {
            if (is_string($value)) {
                $existing[$key] = Storage::disk('s3')->url($value);
            } elseif (is_array($value)) {
                $existing[$key] = $this->mergeFilesRecursively(
                    $existing[$key] ?? [],
                    $value
                );
            }
        }

        return $existing;
    }
}
