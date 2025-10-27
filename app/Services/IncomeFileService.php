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

    /**
     * Handle Rent Files
     */
    public function handleRentFiles(Request $request, array &$attach, array &$data): void
    {
        $rentFiles = $request->file('rent', []);

        foreach ($rentFiles as $index => $files) {
            if ($request->hasFile("rent.$index.rent_files")) {
                // Delete old files
                if (!empty($attach['rent'][$index]['rent_files'])) {
                    foreach ($attach['rent'][$index]['rent_files'] as $oldFile) {
                        Storage::disk('s3')->delete($oldFile);
                    }
                }

                // Store new files
                $paths = [];
                foreach ($files['rent_files'] as $file) {
                    $paths[] = $file->store('rent', 's3');
                }

                // Update both attach & data
                $attach['rent'][$index]['rent_files'] = $paths;
                $data['rent'][$index]['rent_files'] = $paths;
            }
        }
    }
}
