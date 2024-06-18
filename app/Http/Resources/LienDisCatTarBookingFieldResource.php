<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LienDisCatTarBookingFieldResource extends JsonResource
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
            'cat_tarif_id' => $this->cat_tarif_id,
            'nom' => $this->nom,
            'type_champ_form' => $this->type_champ_form,
            'ordre' => $this->ordre,
            'cat_tarif_type' => LienDisCatTarifTypeResource::make($this->whenLoaded('cat_tarif_type')),
            'valeurs' => LienDisCatTarBookingFieldValeurResource::collection($this->whenLoaded('valeurs')),
            'sous_fields' => LienDisCatTarBookingFieldSousFieldResource::collection($this->whenLoaded('sous_fields')),
            'reservation_attributs' => ReservationAttributResource::collection($this->whenLoaded('reservation_attributs')),
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];

    }
}