<?php

namespace App\Http\Resources;

use App\Http\Resources\Traits\HasAttachFiles;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class DeductionResource extends JsonResource
{
    use HasAttachFiles;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        if (!empty($this->attach) && is_array($this->attach)) {
            $data = $this->mergeAttachFiles($data, $this->attach);
        }
        return $data;
    }


}
