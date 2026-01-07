<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeductionFileService
{


    /**
     * @param Request $request
     * @param array $attach
     * @param array $data
     * @param string $input
     * @param string $section
     * @param string $key
     * @return void
     */
    public function handleSingleFileForWeb(Request $request, array &$attach, array &$data, string $input, string $section, string $key): void
    {
        $fullInput = "$section.$key";

        if ($request->hasFile($fullInput)) {
            // Delete old file if exists
            if (!empty($attach[$section][$key])) {
                Storage::disk('s3')->delete($attach[$section][$key]);
            }

            // Store new file
            $path = $request->file($fullInput)->store($section, 's3');

            // Update attach & data
            $attach[$section][$key] = $path;
            $data[$section][$key] = $path;
        }
    }



    /**
     * Handle multiple file uploads for Deduction sections
     */
    public function handleMultipleFilesForWeb(Request $request, array &$attach, array &$data, string $input, string $section, string $key): void
    {
        $fullInput = "$section.$key";

        if ($request->hasFile($fullInput)) {
            // Delete old files
            if (!empty($attach[$section][$key])) {
                foreach ($attach[$section][$key] as $oldFile) {
                    Storage::disk('s3')->delete($oldFile);
                }
            }

            // Store new files
            $paths = [];
            foreach ($request->file($fullInput) as $file) {
                $paths[] = $file->store($section, 's3');
            }

            // Update attach & data
            $attach[$section][$key] = $paths;
            $data[$section][$key] = $paths;
        }
    }



    /**
     * @param Request $request
     * @param array $attach
     * @param array $data
     * @return void
     */
    public function handleTravelExpensesFilesForApi(Request $request, array &$attach, array &$data): void
    {
        if ($request->hasFile('travel_expenses.travel_file')) {
            if (!empty($attach['travel_expenses']['travel_file'])) {
                Storage::disk('s3')->delete($attach['travel_expenses']['travel_file']);
            }

            $path = $request->file('travel_expenses.travel_file')->store('travel_expenses', 's3');

            $attach['travel_expenses']['travel_file'] = $path;
            $data['travel_expenses']['travel_file'] = $path;
        }
    }


    /**
     * Handle file upload for Computer deduction
     */
    public function handleComputerFilesForApi(Request $request, array &$attach, array &$data): void
    {
        if ($request->hasFile('computer.computer_file')) {
            if (!empty($attach['computer']['computer_file'])) {
                Storage::disk('s3')->delete($attach['computer']['computer_file']);
            }

            $path = $request->file('computer.computer_file')->store('computer', 's3');

            $attach['computer']['computer_file'] = $path;
            $data['computer']['computer_file'] = $path;
        }
    }


    /**
     * Handle file upload for Home Office
     */
    public function handleHomeOfficeFilesForApi(Request $request, array &$attach, array &$data): void
    {


        if ($request->hasFile('home_office.home_receipt_file')) {
            if (!empty($attach['home_office']['home_receipt_file'])) {
                Storage::disk('s3')->delete($attach['home_office']['home_receipt_file']);
            }

            $path = $request->file('home_office.home_receipt_file')->store('home_office', 's3');

            $attach['home_office']['home_receipt_file'] = $path;
            $data['home_office']['home_receipt_file'] = $path;
        }

        if ($request->hasFile('home_office.hours_worked_record_file_yes')) {
            if (!empty($attach['home_office']['hours_worked_record_file_yes'])) {
                Storage::disk('s3')->delete(
                    $attach['home_office']['hours_worked_record_file_yes']
                );
            }

            $path = $request
                ->file('home_office.hours_worked_record_file_yes')
                ->store('home_office', 's3');

            $attach['home_office']['hours_worked_record_file_yes'] = $path;
            $data['home_office']['hours_worked_record_file_yes'] = $path;
        }
    }


    /**
     * Handle file upload for Books
     */
    public function handleBooksFilesForApi(Request $request, array &$attach, array &$data): void
    {

        if ($request->hasFile('books.books_file')) {
            if (!empty($attach['books']['books_file'])) {
                Storage::disk('s3')->delete($attach['books']['books_file']);
            }

            $path = $request->file('books.books_file')->store('books', 's3');

            $attach['books']['books_file'] = $path;
            $data['books']['books_file'] = $path;
        }
    }


    /**
     * Handle file upload for Uniforms
     */
    public function handleUniformFiles(Request $request, array &$attach, array &$data): void
    {
        if ($request->hasFile('uniforms.uniform_receipt')) {
            if (!empty($attach['uniforms']['uniform_receipt'])) {
                Storage::disk('s3')->delete($attach['uniforms']['uniform_receipt']);
            }

            $path = $request->file('uniforms.uniform_receipt')->store('uniforms', 's3');

            $attach['uniforms']['uniform_receipt'] = $path;
            $data['uniforms']['uniform_receipt'] = $path;
        }
    }


    /**
     * Handle file upload for Education
     */
    public function handleEducationFilesForApi(Request $request, array &$attach, array &$data): void
    {
        if ($request->hasFile('education.edu_file')) {
            if (!empty($attach['education']['edu_file'])) {
                Storage::disk('s3')->delete($attach['education']['edu_file']);
            }

            $path = $request->file('education.edu_file')->store('education', 's3');

            $attach['education']['edu_file'] = $path;
            $data['education']['edu_file'] = $path;
        }
    }


    /**
     * Handle file upload for Union Fees
     */
    public function handleUnionFeesFilesForApi(Request $request, array &$attach, array &$data): void
    {
        if ($request->hasFile('union_fees.file')) {
            if (!empty($attach['union_fees']['file'])) {
                Storage::disk('s3')->delete($attach['union_fees']['file']);
            }

            $path = $request->file('union_fees.file')->store('union_fees', 's3');

            $attach['union_fees']['file'] = $path;
            $data['union_fees']['file'] = $path;
        }
    }


    /**
     * Handle file upload for Sun Protection
     */
    public function handleSunProtectionFilesForApi(Request $request, array &$attach, array &$data): void
    {
        if ($request->hasFile('sun_protection.sun_file')) {
            if (!empty($attach['sun_protection']['sun_file'])) {
                Storage::disk('s3')->delete($attach['sun_protection']['sun_file']);
            }

            $path = $request->file('sun_protection.sun_file')->store('sun_protection', 's3');

            $attach['sun_protection']['sun_file'] = $path;
            $data['sun_protection']['sun_file'] = $path;
        }
    }


    /**
     * Handle multiple files for Low Value Pool
     */
    public function handleLowValuePoolFilesForApi(Request $request, array &$attach, array &$data): void
    {
        if ($request->hasFile('low_value_pool.files')) {
            // Delete old files
            if (!empty($attach['low_value_pool']['files'])) {
                foreach ($attach['low_value_pool']['files'] as $oldFile) {
                    Storage::disk('s3')->delete($oldFile);
                }
            }

            $paths = [];
            foreach ($request->file('low_value_pool.files') as $file) {
                $paths[] = $file->store('low_value_pool', 's3');
            }

            $attach['low_value_pool']['files'] = $paths;
            $data['low_value_pool']['files'] = $paths;
        }
    }
}
