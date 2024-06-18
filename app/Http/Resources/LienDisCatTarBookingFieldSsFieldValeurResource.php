<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LienDisCatTarBookingFieldSsFieldValeurResource extends JsonResource
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
            'sousfield_id' => $this->sousfield_id,
            'valeur' => $this->valeur,
            'ordre' => $this->ordre,
            'inclus_all' => $this->inclus_all,
            'booking_sous_field' => LienDisCatTarBookingFieldSousFieldResource::make($this->whenLoaded('booking_sous_field')),
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];

    }
}
