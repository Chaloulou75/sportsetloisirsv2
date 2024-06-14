<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StructureProduitResource extends JsonResource
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
            'discipline_id' => $this->discipline_id,
            'categorie_id' => $this->categorie_id,
            'structure_id' => $this->structure_id,
            'activite_id' => $this->activite_id,
            'lieu_id' => $this->lieu_id,
            'actif' => $this->actif,
            'reservable' => $this->reservable,
            'minimum_amount' => $this->minimum_amount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'structure' => StructureResource::make($this->whenLoaded('structure')),
            'discipline' => ListDisciplineResource::make($this->whenLoaded('discipline')),
            'categorie' => LienDisciplineCategorieResource::make($this->whenLoaded('categorie')),
            'activite' => StructureActiviteResource::make($this->whenLoaded('activite')),
            'adresse' => StructureAddressResource::make($this->whenLoaded('adresse')),
            'criteres' => StructureProduitCritereResource::collection($this->whenLoaded('criteres')),
            'sous_criteres' => StructureProduitSousCritereResource::collection($this->whenLoaded('sous_criteres')),
            'cat_tarifs' => StructureCatTarifResource::collection($this->whenLoaded('cat_tarifs')),
            'plannings' => StructurePlanningResource::collection($this->whenLoaded('plannings')),
            'reservations' => ProductReservationResource::collection($this->whenLoaded('reservations')),
        ];

    }
}
