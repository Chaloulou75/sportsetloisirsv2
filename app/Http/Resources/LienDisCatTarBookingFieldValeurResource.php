<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LienDisCatTarBookingFieldValeurResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cat_tar_field_id' => $this->cat_tar_field_id,
            'valeur' => $this->valeur,
            'ordre' => $this->ordre,
            'booking_field' => LienDisCatTarBookingFieldResource::make($this->whenLoaded('booking_field')),
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];

    }
}
