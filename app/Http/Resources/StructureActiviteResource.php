<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StructureActiviteResource extends JsonResource
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
            'titre' => $this->titre,
            'description' => $this->description,
            'image' => $this->image,
            'image_url' => $this->image_url,
            'slug_title' => $this->slug_title,
            'actif' => $this->actif,
            'discipline_id' => $this->discipline_id,
            'categorie_id' => $this->categorie_id,
            'structure_id' => $this->structure_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'structure' => StructureResource::make($this->whenLoaded('structure')),
            'discipline' => ListDisciplineResource::make($this->whenLoaded('discipline')),
            'categorie' => LienDisciplineCategorieResource::make($this->whenLoaded('categorie')),
            'produits' => StructureProduitResource::collection($this->whenLoaded('produits')),
            'plannings' => StructurePlanningResource::collection($this->whenLoaded('plannings')),
            'instructeurs' => UserResource::collection($this->whenLoaded('instructeurs')),
            // 'dates' => StructureActiviteDateResource::collection($this->whenLoaded('dates')),
            'reservations' => ProductReservationResource::collection($this->whenLoaded('reservations')),
        ];

    }
}
