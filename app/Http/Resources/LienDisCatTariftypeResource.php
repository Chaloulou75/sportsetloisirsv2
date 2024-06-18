<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LienDisCatTariftypeResource extends JsonResource
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
            'nom' => $this->nom,
            'show_planning' => $this->show_planning,
            'discipline' =>  ListDisciplineResource::make($this->whenLoaded('discipline')),
            'categorie' =>  LienDisciplineCategorieResource::make($this->whenLoaded('categorie')),
            'tarif_type' =>  ListeTarifTypeResource::make($this->whenLoaded('tarif_type')),
            'tarif_attributs' => LienDisCatTartypAttributResource::collection($this->whenLoaded('tarif_attributs')),
            'tarif_booking_fields' => LienDisCatTarBookingFieldResource::collection($this->whenLoaded('tarif_booking_fields')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
