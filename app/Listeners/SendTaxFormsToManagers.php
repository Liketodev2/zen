<?php

namespace App\Listeners;

use App\Events\TaxPaymentSucceeded;
use App\Mail\FormsSubmittedWithAttachments;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendTaxFormsToManagers implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @param TaxPaymentSucceeded $event
     * @return void
     */
    public function handle(TaxPaymentSucceeded $event): void
    {
        $tax = $event->tax->load(['user', 'other', 'basicInfo', 'deduction', 'income']);

        // Collect attachments separately by relation
        $otherFiles     = $this->collectAttachments($tax->other->attach ?? null);
        $deductionFiles = $this->collectAttachments($tax->deduction->attach ?? null);
        $incomeFiles    = $this->collectAttachmentsIncomeFiles($tax->income->attach ?? null);

        $managerEmails = array_filter(
            array_map('trim', explode(',', env('MANAGER_EMAILS', 'manager1@example.com')))
        );

        foreach ($managerEmails as $email) {
            Mail::to($email)->queue(
                new FormsSubmittedWithAttachments(
                    $tax,
                    $otherFiles,
                    $deductionFiles,
                    $incomeFiles,
                    $tax->other,
                    $tax->basicInfo,
                    $tax->income,
                    $tax->deduction
                )
            );
        }
    }


    /**
     * @param $attachData
     * @return array
     */
    private function collectAttachments($attachData): array
    {
        $files = [];

        $data = is_array($attachData)
            ? $attachData
            : json_decode($attachData ?? '[]', true);

        if (empty($data)) {
            return $files;
        }

        // Use recursive iterator to handle nested structure
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveArrayIterator($data),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $value) {
            // Only collect string values that look like file paths
            if (is_string($value) && !empty($value)) {
                // Skip keys/values that are not file paths (e.g., 'input_option', 'family_status', etc.)
                if (strpos($value, '/') !== false || strpos($value, '\\') !== false) {
                    $files[] = $value;
                }
            }
        }

        return array_unique($files);
    }


    /**
     * @param $attachData
     * @return array
     */
    private function collectAttachmentsIncomeFiles($attachData): array
    {
        $files = [];

        $data = is_array($attachData)
            ? $attachData
            : json_decode($attachData ?? '[]', true);

        if (empty($data)) {
            return $files;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveArrayIterator($data),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $value) {
            if (is_string($value) && !empty($value)) {
                // Skip keys/values that are not file paths
                if (strpos($value, '/') !== false || strpos($value, '\\') !== false) {
                    $files[] = $value;
                }
            }
        }

        return array_unique($files);
    }



    /**
     * @param TaxPaymentSucceeded $event
     * @param $exception
     * @return void
     */
    public function failed(TaxPaymentSucceeded $event, $exception)
    {
        // Reset tax status if the listener job fails
        $event->tax->update([
            'form_status' => 'incomplete',
        ]);

        Log::error("Failed sending tax forms for Tax #{$event->tax->id}: " . $exception->getMessage());
    }

}
