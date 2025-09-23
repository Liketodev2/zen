<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaxReturnResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'form_status' => $this->form_status,
            'payment_status' => $this->payment_status,
            'user_id'    => new UserResource($this->whenLoaded('user')),
            'basic_info' => new BasicInfoResource($this->whenLoaded('basicInfo')),
            'income'     => new IncomeResource($this->whenLoaded('income')),
            'deduction'  => new DeductionResource($this->whenLoaded('deduction')),
            'other'      => new OtherResource($this->whenLoaded('other')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
