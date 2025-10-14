<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IncomeFileService
{
    /**
     * Handle all income-related file uploads.
     *
     * @param Request $request
     * @param array $attach
     * @return array Updated $attach array
     */
    public function handleAllFiles(Request $request, array $attach = []): array
    {
        $attach = $this->handleCapitalGainsFiles($request, $attach);
        $attach = $this->handleManagedFundsFiles($request, $attach);
        $attach = $this->handleTerminationPaymentsFiles($request, $attach);
        $attach = $this->handleRentFiles($request, $attach);

        return $attach;
    }


    /**
     * Capital Gains file handling.
     */
    private function handleCapitalGainsFiles(Request $request, array $attach): array
    {
        if ($request->hasFile('capital_gains.cgt_attachment')) {
            // Delete old file if exists
            if (!empty($attach['capital_gains_attachment'])) {
                Storage::disk('s3')->delete($attach['capital_gains_attachment']);
            }

            $file = $request->file('capital_gains.cgt_attachment');
            $attach['capital_gains_attachment'] = $file->store('capital_gains', 's3');
        }

        return $attach;
    }


    /**
     * Managed Funds file handling.
     */
    private function handleManagedFundsFiles(Request $request, array $attach): array
    {
        if ($request->hasFile('managed_fund_files')) {
            if (!empty($attach['managed_fund_files'])) {
                foreach ($attach['managed_fund_files'] as $oldFile) {
                    Storage::disk('s3')->delete($oldFile);
                }
            }

            $paths = [];
            foreach ($request->file('managed_fund_files') as $file) {
                $paths[] = $file->store('managed_funds', 's3');
            }
            $attach['managed_fund_files'] = $paths;
        }

        return $attach;
    }


    /**
     * Termination Payments file handling.
     */
    private function handleTerminationPaymentsFiles(Request $request, array $attach): array
    {
        $etpFiles = $request->file('termination_payments', []);

        foreach ($etpFiles as $index => $files) {
            if ($request->hasFile("termination_payments.$index.etp_files")) {
                if (!empty($attach['termination_payments'][$index]['etp_files'])) {
                    foreach ($attach['termination_payments'][$index]['etp_files'] as $oldFile) {
                        Storage::disk('s3')->delete($oldFile);
                    }
                }

                $paths = [];
                foreach ($files['etp_files'] as $file) {
                    $paths[] = $file->store('termination_payments', 's3');
                }

                $attach['termination_payments'][$index]['etp_files'] = $paths;
            }
        }

        return $attach;
    }


    /**
     * Rent file handling.
     */
    private function handleRentFiles(Request $request, array $attach): array
    {
        $rentFiles = $request->file('rent', []);

        foreach ($rentFiles as $index => $files) {
            if ($request->hasFile("rent.$index.rent_files")) {
                if (!empty($attach['rent'][$index]['rent_files'])) {
                    foreach ($attach['rent'][$index]['rent_files'] as $oldFile) {
                        Storage::disk('s3')->delete($oldFile);
                    }
                }

                $paths = [];
                foreach ($files['rent_files'] as $file) {
                    $paths[] = $file->store('rent', 's3');
                }

                $attach['rent'][$index]['rent_files'] = $paths;
            }
        }

        return $attach;
    }
}
