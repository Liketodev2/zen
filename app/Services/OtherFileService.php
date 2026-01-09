<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OtherFileService
{
    /**
     * Delete file safely
     */
    public function deleteFile(?string $path): void
    {
        if ($path && Storage::disk('s3')->exists($path)) {
            Storage::disk('s3')->delete($path);
        }
    }

    /**
     * Handle single file upload
     */
    public function handleSingleFile(
        Request $request,
        array &$attach,
        array &$data,
        string $section,
        string $key,
        string $disk = 's3'
    ): void {
        $input = "$section.$key";

        if ($request->hasFile($input)) {
            // Delete old
            if (!empty($attach[$section][$key])) {
                $this->deleteFile($attach[$section][$key]);
            }

            // Store new
            $path = $request->file($input)->store($section, $disk);

            $attach[$section][$key] = $path;
            $data[$section][$key]   = $path;
        }
    }

    /**
     * Handle multiple file uploads
     */
    public function handleMultipleFiles(
        Request $request,
        array &$attach,
        array &$data,
        string $section,
        string $key,
        string $disk = 's3'
    ): void {
        $input = "$section.$key";

        if ($request->hasFile($input)) {
            // Delete old files
            if (!empty($attach[$section][$key])) {
                foreach ($attach[$section][$key] as $oldFile) {
                    $this->deleteFile($oldFile);
                }
            }

            // Store new files
            $paths = [];
            foreach ($request->file($input) as $file) {
                $paths[] = $file->store($section, $disk);
            }

            $attach[$section][$key] = $paths;
            $data[$section][$key]   = $paths;
        }
    }

    /**
     * Handle "additional_file" uploads
     */
    public function handleAdditionalFiles(
        Request $request,
        array &$attach,
        array &$data
    ): void {
        if ($request->hasFile('documents_to_attach_files')) {
            // Delete old
            if (!empty($attach['documents_to_attach_files'])) {
                foreach ($attach['documents_to_attach_files'] as $oldFile) {
                    $this->deleteFile($oldFile);
                }
            }

            $paths = [];
            foreach ($request->file('documents_to_attach_files') as $file) {
                $paths[] = $file->store('documents_to_attach_files', 's3');
            }

            $attach['documents_to_attach_files'] = $paths;
            $data['documents_to_attach_files']   = $paths;
        }
    }


    /**
     * @param Request $request
     * @param array $attach
     * @return void
     */
    public function handlePrivateHealthInsuranceFiles(
        Request $request,
        array &$attach
    ): void {
        $fileKeys = [
            'statement_file',
            'private_health_statement',
        ];

        foreach ($fileKeys as $key) {
            if ($request->hasFile("private_health_insurance.$key")) {

                // Delete old file(s)
                if (!empty($attach['private_health_insurance'][$key])) {
                    if (is_array($attach['private_health_insurance'][$key])) {
                        foreach ($attach['private_health_insurance'][$key] as $old) {
                            $this->deleteFile($old);
                        }
                    } else {
                        $this->deleteFile($attach['private_health_insurance'][$key]);
                    }
                }

                // Store new file
                $attach['private_health_insurance'][$key] =
                    $request->file("private_health_insurance.$key")
                        ->store('private_health_insurance', 's3');
            }
        }
    }


    /**
     * @param Request $request
     * @param array $attach
     * @param array $data
     * @return void
     */
    public function handleMedicareCertificate(
        Request $request,
        array &$attach,
        array &$data
    ): void {
        if ($request->hasFile('medicare_reduction_exemption.medicare_certificate_file')) {
            if (!empty($attach['medicare_reduction_exemption']['medicare_certificate_file'])) {
                $this->deleteFile($attach['medicare_reduction_exemption']['medicare_certificate_file']);
            }

            $path = $request
                ->file('medicare_reduction_exemption.medicare_certificate_file')
                ->store('medicare_reduction_exemption', 's3');

            $attach['medicare_reduction_exemption']['medicare_certificate_file'] = $path;
            $data['medicare_reduction_exemption']['medicare_certificate_file']   = $path;
        }
    }


    /**
     * @param Request $request
     * @param array $attach
     * @param array $data
     * @return void
     */
    public function handleMedicalExpenseFile(
        Request $request,
        array &$attach,
        array &$data
    ): void {
        if ($request->hasFile('medical_expenses_offset.medical_expense_file')) {
            if (!empty($attach['medical_expenses_offset']['medical_expense_file'])) {
                $this->deleteFile($attach['medical_expenses_offset']['medical_expense_file']);
            }

            $path = $request
                ->file('medical_expenses_offset.medical_expense_file')
                ->store('medical_expenses_offset', 's3');

            $attach['medical_expenses_offset']['medical_expense_file'] = $path;
            $data['medical_expenses_offset']['medical_expense_file']   = $path;
        }
    }


    /**
     * @param Request $request
     * @param array $attach
     * @param array $data
     * @return void
     */
    public function handlePrivateHealthInsuranceFilesForApi(Request $request, array &$attach, array &$data): void
    {
        $fileKeys = [
            'statement_file',
            'private_health_statement',
        ];

        foreach ($fileKeys as $key) {
            if ($request->hasFile("private_health_insurance.$key")) {
                // Delete old file if exists
                if (!empty($attach['private_health_insurance'][$key])) {
                    $this->deleteFile($attach['private_health_insurance'][$key]);
                }

                // Store new file
                $path = $request->file("private_health_insurance.$key")->store('private_health_insurance', 's3');

                $attach['private_health_insurance'][$key] = $path;
                $data['private_health_insurance'][$key] = $path;
            }
        }
    }


    /**
     * @param Request $request
     * @param array $attach
     * @param array $data
     * @return void
     */
    public function handleMedicareReductionExemptionFilesForApi(Request $request, array &$attach, array &$data): void
    {
        if ($request->hasFile('medicare_reduction_exemption.medicare_certificate_file')) {
            if (!empty($attach['medicare_reduction_exemption']['medicare_certificate_file'])) {
                $this->deleteFile($attach['medicare_reduction_exemption']['medicare_certificate_file']);
            }

            $path = $request->file('medicare_reduction_exemption.medicare_certificate_file')->store('medicare_reduction_exemption', 's3');

            $attach['medicare_reduction_exemption']['medicare_certificate_file'] = $path;
            $data['medicare_reduction_exemption']['medicare_certificate_file'] = $path;
        }
    }


    /**
     * @param Request $request
     * @param array $attach
     * @param array $data
     * @return void
     */
    public function handleMedicalExpensesOffsetFilesForApi(Request $request, array &$attach, array &$data): void
    {
        if ($request->hasFile('medical_expenses_offset.medical_expense_file')) {
            if (!empty($attach['medical_expenses_offset']['medical_expense_file'])) {
                $this->deleteFile($attach['medical_expenses_offset']['medical_expense_file']);
            }

            $path = $request->file('medical_expenses_offset.medical_expense_file')->store('medical_expenses_offset', 's3');

            $attach['medical_expenses_offset']['medical_expense_file'] = $path;
            $data['medical_expenses_offset']['medical_expense_file'] = $path;
        }
    }


    /**
     * @param Request $request
     * @param array $attach
     * @param array $data
     * @return void
     */
    public function handleDocumentsToAttachFilesForApi(Request $request, array &$attach, array &$data): void
    {
        if ($request->hasFile('documents_to_attach_files')) {
            // Initialize array if it doesn't exist
            if (!isset($attach['documents_to_attach_files'])) {
                $attach['documents_to_attach_files'] = [];
            }

            // Keep existing files
            $paths = is_array($attach['documents_to_attach_files']) ? $attach['documents_to_attach_files'] : [];

            // Add new files
            foreach ($request->file('documents_to_attach_files') as $file) {
                $paths[] = $file->store('documents_to_attach_files', 's3');
            }

            $attach['documents_to_attach_files'] = $paths;
            $data['documents_to_attach_files'] = $paths;
        }
    }
}
