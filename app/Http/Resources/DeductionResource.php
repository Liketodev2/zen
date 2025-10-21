<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class DeductionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        // Convert attach file paths to full S3 URLs
        if (!empty($data['attach'])) {
            $data['attach'] = $this->transformAttach($data['attach']);
        }
        return $data;
    }



    /**
     * @param $attach
     * @return mixed
     */
    protected function transformAttach($attach)
    {
        if (is_array($attach)) {
            foreach ($attach as $key => $value) {
                if (is_string($value)) {
                    // Convert file path to full S3 URL
                    $attach[$key] = Storage::disk('s3')->url($value);
                } elseif (is_array($value)) {
                    // Recurse deeper into nested arrays
                    $attach[$key] = $this->transformAttach($value);
                }
            }
        }

        return $attach;
    }
}
