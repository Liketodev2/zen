<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FormsSubmittedWithAttachments extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $tax;
    public array $other;
    public array $basicInfo;
    public array $income;
    public array $deduction;

    // Uploaded file paths (not attached; used to build links)
    public array $otherFiles;
    public array $deductionFiles;
    public array $incomeFiles;

    // Links for uploaded files
    public array $otherLinks = [];
    public array $deductionLinks = [];
    public array $incomeLinks = [];

    // Links for generated PDFs
    public ?string $otherPdf = null;
    public ?string $basicInfoPdf = null;
    public ?string $incomePdf = null;
    public ?string $deductionPdf = null;

    /**
     * @var array|string[]
     */
    private array $hiddenKeys = ['id', 'tax_return_id', 'created_at', 'updated_at', 'attach', 'deleted_at'];

    public function __construct(
        $tax,
        array $otherFiles = [],
        array $deductionFiles = [],
        array $incomeFiles = [],
        $other = null,
        $basicInfo = null,
        $income = null,
        $deduction = null
    ) {
        $this->tax = $tax;

        $this->otherFiles     = $otherFiles;
        $this->deductionFiles = $deductionFiles;
        $this->incomeFiles    = $incomeFiles;

        // Normalize and clean form data for PDFs
        $this->other     = $this->normalizeAndClean($other);
        $this->basicInfo = $this->normalizeAndClean($basicInfo);
        $this->income    = $this->normalizeAndClean($income);
        $this->deduction = $this->normalizeAndClean($deduction);

        // Build public download links for the uploaded files
        $this->otherLinks     = $this->makeDownloadLinks($this->otherFiles, 'Other');
        $this->deductionLinks = $this->makeDownloadLinks($this->deductionFiles, 'Deduction');
        $this->incomeLinks    = $this->makeDownloadLinks($this->incomeFiles, 'Income');

        // Generate PDFs and store download links
        $this->otherPdf     = $this->generatePdfLink($this->other, 'Other Data', 'OtherFormData.pdf', $this->otherLinks);
        $this->basicInfoPdf = $this->generatePdfLink($this->basicInfo, 'Basic Info', 'BasicInfoFormData.pdf');
        $this->incomePdf    = $this->generatePdfLink($this->income, 'Income Info', 'IncomeFormData.pdf', $this->incomeLinks);
        $this->deductionPdf = $this->generatePdfLink($this->deduction, 'Deduction Info', 'DeductionFormData.pdf', $this->deductionLinks);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Tax Forms Submitted - Tax ID #{$this->tax->id}"
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.forms_submitted',
            with: [
                'tax'            => $this->tax,
                // uploaded file links
                'otherLinks'     => $this->otherLinks,
                'deductionLinks' => $this->deductionLinks,
                'incomeLinks'    => $this->incomeLinks,
                // pdf links
                'otherPdf'       => $this->otherPdf,
                'basicInfoPdf'   => $this->basicInfoPdf,
                'incomePdf'      => $this->incomePdf,
                'deductionPdf'   => $this->deductionPdf,
            ],
        );
    }

    public function attachments(): array
    {
        // ❌ No attachments — only links in email body
        return [];
    }


    /**
     * @param array $data
     * @param string $name
     * @param string $fileName
     * @param array $links
     * @return string|null
     */
    private function generatePdfLink(array $data, string $name, string $fileName, array $links = []): ?string
    {
        if (empty($data)) {
            return null;
        }

        try {
            $pdfContent = Pdf::loadView('pdf.pdf_data', [
                'formData' => $data,
                'tax'      => $this->tax,
                'name'     => $name,
                'links'    => $links,
            ])->output();

            if (!$pdfContent) {
                return null;
            }

            $path = "pdfs/{$this->tax->id}_{$fileName}";
            Storage::disk('s3')->put($path, $pdfContent);

            return Storage::disk('s3')->url($path);

        } catch (\Throwable $e) {
            // log error but don't kill the mail job
            Log::error("Failed to generate PDF [$fileName] for Tax #{$this->tax->id}: " . $e->getMessage());
            return null;
        }
    }


    /**
     * @param array $files
     * @param string $prefix
     * @return array
     */
    private function makeDownloadLinks(array $files, string $prefix): array
    {
        $links = [];
        
        foreach ($files as $path) {
            // Skip if path is empty or not a string
            if (empty($path) || !is_string($path)) {
                continue;
            }
            
            try {
                $signedUrl = Storage::disk('s3')->url($path);
                
                $links[] = [
                    'label' => $prefix . ' - ' . basename($path),
                    'url'   => $signedUrl,
                ];
            } catch (\Throwable $e) {
                Log::warning("Failed to generate URL for file: {$path}. Error: " . $e->getMessage());
            }
        }
        
        return $links;
    }

    /**
     * Normalize and clean form data for PDFs.
     */
    private function normalizeAndClean($data): array
    {
        if ($data instanceof Collection) {
            $raw = $data->toArray();
        } elseif ($data instanceof Model) {
            // Single model - just convert to array, don't wrap in another array
            $raw = $data->toArray();
        } elseif (is_array($data)) {
            $raw = $data;
        } else {
            $raw = [];
        }

        // If it's a single model's data (has keys like 'id', 'created_at', etc.), wrap it
        if (!empty($raw) && !isset($raw[0]) && isset($raw['id'])) {
            $cleaned = $this->cleanAndHumanize($raw);
            return empty($cleaned) ? [] : [$cleaned];
        }

        // Otherwise, process as array of items
        return array_values(array_filter(array_map(
            fn($item) => $this->cleanAndHumanize((array)$item),
            is_array($raw) && isset($raw[0]) ? $raw : [$raw]
        )));
    }

    /**
     * Clean model data and make labels human-readable.
     */
    private function cleanAndHumanize(array $data): array
    {
        $out = [];

        foreach ($data as $key => $value) {
            if (in_array(strtolower($key), $this->hiddenKeys, true)) {
                continue;
            }

            if (is_null($value) || $value === '' || (is_array($value) && empty($value))) {
                continue;
            }

            $label = ucwords(preg_replace('/[_\-]+/', ' ', (string) $key));

            $out[$label] = is_array($value)
                ? $this->cleanAndHumanize($value)
                : $value;
        }

        return $out;
    }


    /**
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        $this->tax->update([
            'form_status' => 'incomplete',
        ]);

        Log::error("Mailable failed for Tax #{$this->tax->id}: " . $exception->getMessage());
    }
}
