<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LienDisCatTarBookingFieldSousFieldResource extends JsonResource
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
            'booking_field_id' => $this->booking_field_id,
            'nom' => $this->nom,
            'type_champ_form' => $this->type_champ_form,
            'type_champ_id' => $this->type_champ_id,
            'ordre' => $this->ordre,
            'booking_field' => LienDisCatTarBookingFieldResource::make($this->whenLoaded('booking_field')),
            'type_champ' => TypeChampResource::make($this->whenLoaded('type_champ')),
            'valeurs' => LienDisCatTarBookingFieldSsFieldValeurResource::collection($this->whenLoaded('valeurs')),
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
