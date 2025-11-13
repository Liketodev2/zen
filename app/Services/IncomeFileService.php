<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IncomeFileService
{
    /**
     * Handle Capital Gains File Upload
     */
    public function handleCapitalGainsFiles(Request $request, array &$attach, array &$data): void
    {
        if ($request->hasFile('capital_gains.cgt_attachment')) {
            // Delete old file if exists
            if (!empty($attach['capital_gains_attachment'])) {
                Storage::disk('s3')->delete($attach['capital_gains_attachment']);
            }

            // Store new file
            $file = $request->file('capital_gains.cgt_attachment');
            $path = $file->store('capital_gains', 's3');

            // Update both attach & data
            $attach['capital_gains_attachment'] = $path;
            $data['capital_gains']['cgt_attachment'] = $path;
        }
    }

    /**
     * Handle Managed Funds Files
     */
    public function handleManagedFundsFiles(Request $request, array &$attach, array &$data): void
    {
        if ($request->hasFile('managed_fund_files')) {
            // Delete old files
            if (!empty($attach['managed_fund_files'])) {
                foreach ($attach['managed_fund_files'] as $oldFile) {
                    Storage::disk('s3')->delete($oldFile);
                }
            }

            // Store new files
            $files = $request->file('managed_fund_files');
            $paths = [];
            foreach ($files as $file) {
                $paths[] = $file->store('managed_funds', 's3');
            }

            // Update both attach & data
            $attach['managed_fund_files'] = $paths;
            $data['managed_funds']['managed_fund_files'] = $paths;
        }
    }

    /**
     * Handle Termination Payments Files
     */
    public function handleTerminationPaymentsFiles(Request $request, array &$attach, array &$data): void
    {
        $etpFiles = $request->file('termination_payments', []);

        foreach ($etpFiles as $index => $files) {
            if ($request->hasFile("termination_payments.$index.etp_files")) {
                // Delete old files
                if (!empty($attach['termination_payments'][$index]['etp_files'])) {
                    foreach ($attach['termination_payments'][$index]['etp_files'] as $oldFile) {
                        Storage::disk('s3')->delete($oldFile);
                    }
                }

                // Store new files
                $paths = [];
                foreach ($files['etp_files'] as $file) {
                    $paths[] = $file->store('termination_payments', 's3');
                }

                // Update both attach & data
                $attach['termination_payments'][$index]['etp_files'] = $paths;
                $data['termination_payments'][$index]['etp_files'] = $paths;
            }
        }
    }


    public function handleTerminationPaymentsFilesForApi(Request $request, array &$attach, array &$data): void
    {
        if (!isset($attach['termination_payments'])) {
            $attach['termination_payments'] = [];
        }

        foreach ($data['termination_payments'] as $index => &$etpItem) {

            if ($request->hasFile("termination_payments.$index.etp_files")) {

                // Delete old files only for this item
                if (!empty($attach['termination_payments'][$index]['etp_files'])) {
                    foreach ($attach['termination_payments'][$index]['etp_files'] as $oldFile) {
                        Storage::disk('s3')->delete($oldFile);
                    }
                }

                // Upload new files
                $paths = [];
                foreach ($request->file("termination_payments.$index.etp_files") as $file) {
                    $paths[] = $file->store('termination_payments', 's3');
                }

                $attach['termination_payments'][$index]['etp_files'] = $paths;
                $etpItem['etp_files'] = $paths;

            } else {
                // Keep existing files if no new files were uploaded
                if (!empty($attach['termination_payments'][$index]['etp_files'])) {
                    $etpItem['etp_files'] = $attach['termination_payments'][$index]['etp_files'];
                }
            }
        }
    }



    /**
     * @param Request $request
     * @param array $attach
     * @param array $data
     * @return void
     */
    public function handleRentFiles(Request $request, array &$attach, array &$data): void
    {
        $newRentData = $data['rent'] ?? [];

        // Delete old rent items that no longer exist in the request
        if (!empty($attach['rent'])) {
            foreach ($attach['rent'] as $index => $oldRent) {
                if (!isset($newRentData[$index])) {
                    // Delete files
                    if (!empty($oldRent['rent_files'])) {
                        foreach ($oldRent['rent_files'] as $file) {
                            Storage::disk('s3')->delete($file);
                        }
                    }
                    // Remove from attach
                    unset($attach['rent'][$index]);
                }
            }
        }

        // Handle new/updated rent files
        foreach ($newRentData as $index => &$rentItem) {
            if ($request->hasFile("rent.$index.rent_files")) {
                // Delete old files for this item
                if (!empty($attach['rent'][$index]['rent_files'])) {
                    foreach ($attach['rent'][$index]['rent_files'] as $oldFile) {
                        Storage::disk('s3')->delete($oldFile);
                    }
                }

                // Upload new files
                $paths = [];
                foreach ($request->file("rent.$index.rent_files") as $file) {
                    $paths[] = $file->store('rent', 's3');
                }

                $attach['rent'][$index]['rent_files'] = $paths;
                $rentItem['rent_files'] = $paths;
            } else {
                // Keep existing files if no new files
                if (!empty($attach['rent'][$index]['rent_files'])) {
                    $rentItem['rent_files'] = $attach['rent'][$index]['rent_files'];
                }
            }
        }

        // Reindex attach array to match new rent data
        $attach['rent'] = array_values($attach['rent']);
        $data['rent'] = array_values($newRentData);
    }



    /**
     * @param Request $request
     * @param array $attach
     * @param array $data
     * @return void
     */
    public function handleRentFilesForApi(Request $request, array &$attach, array &$data): void
    {
        if (!isset($attach['rent'])) {
            $attach['rent'] = [];
        }

        foreach ($data['rent'] as $index => &$rentItem) {

            // If new files are uploaded for this rent item
            if ($request->hasFile("rent.$index.rent_files")) {

                // Delete old files only for this item
                if (!empty($attach['rent'][$index]['rent_files'])) {
                    foreach ($attach['rent'][$index]['rent_files'] as $oldFile) {
                        Storage::disk('s3')->delete($oldFile);
                    }
                }

                // Upload new files
                $paths = [];
                foreach ($request->file("rent.$index.rent_files") as $file) {
                    $paths[] = $file->store('rent', 's3');
                }

                $attach['rent'][$index]['rent_files'] = $paths;
                $rentItem['rent_files'] = $paths;

            } else {
                // Keep existing files if no new files were uploaded
                if (!empty($attach['rent'][$index]['rent_files'])) {
                    $rentItem['rent_files'] = $attach['rent'][$index]['rent_files'];
                }
            }
        }
    }

}
