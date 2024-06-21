<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LienDisciplineCategorieResource extends JsonResource
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
            'slug' => $this->slug,
            'nom_categorie_pro' => $this->nom_categorie_pro,
            'nom_categorie_client' => $this->nom_categorie_client,
            'discipline_id' => $this->discipline_id,
            'categorie_id' => $this->categorie_id,
            'discipline' => ListDisciplineResource::make($this->whenLoaded('discipline')),
            'categorie' => CategorieResource::make($this->whenLoaded('categorie')),
            'str_categories' => StructureCategorieResource::collection($this->whenLoaded('str_categories')),
            'str_activites' => StructureActiviteResource::collection($this->whenLoaded('str_activites')),
            'structures_produits' => StructureProduitResource::collection($this->whenLoaded('structures_produits')),
            'structures_produits_count' => $this->whenCounted('structures_produits'),
            'criteres' => LienDisciplineCategorieCritereResource::collection($this->whenLoaded('criteres')),
            'tarif_types' => LienDisCatTariftypeResource::collection($this->whenLoaded('tarif_types')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
