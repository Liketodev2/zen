<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeductionFileService
{
    /**
     * Handle single file uploads (e.g. computer.computer_file)
     */
    public function handleSingleFile(Request $request, array &$attach, array &$data, string $input, string $folder): void
    {
        [$field, $key] = explode('.', $input);

        if ($request->hasFile($input)) {
            // Delete old file if exists
            if (!empty($attach[$field][$key])) {
                Storage::disk('s3')->delete($attach[$field][$key]);
            }

            // Upload new file
            $path = $request->file($input)->store($folder, 's3');

            // Update both structures
            $attach[$field][$key] = $path;
            $data[$field][$key] = $path;
        }
    }

    /**
     * Handle multiple files (like low_value_pool.files.*)
     */
    public function handleMultipleFiles(Request $request, array &$attach, array &$data, string $input, string $folder): void
    {
        if ($request->hasFile($input)) {
            // Delete old files
            if (!empty($attach[$folder]['files'])) {
                foreach ($attach[$folder]['files'] as $oldFile) {
                    Storage::disk('s3')->delete($oldFile);
                }
            }

            // Upload new ones
            $paths = [];
            foreach ($request->file($input) as $file) {
                $paths[] = $file->store($folder, 's3');
            }

            // Update attach & data
            $attach[$folder]['files'] = $paths;
            $data[$folder]['files'] = $paths;
        }
    }
}
