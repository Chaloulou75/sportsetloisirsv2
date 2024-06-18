<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StructurePlanningResource extends JsonResource
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
            'title' => $this->title,
            'start' => $this->start,
            'end' => $this->end,
            'structure' => StructureResource::make($this->whenLoaded('structure')),
            'categorie' =>  LienDisciplineCategorieResource::make($this->whenLoaded('categorie')),
            'discipline' =>  ListDisciplineResource::make($this->whenLoaded('discipline')),
            'activite' =>  StructureActiviteResource::make($this->whenLoaded('activite')),
            'produit' =>  StructureProduitResource::make($this->whenLoaded('produit')),
            'reservations' => ProductReservationResource::collection($this->whenLoaded('reservations')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
