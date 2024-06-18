<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StructureProduitCritereResource extends JsonResource
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
            'structure_id' => $this->structure_id,
            'discipline_id' => $this->discipline_id,
            'categorie_id' => $this->categorie_id,
            'activite_id' => $this->activite_id,
            'produit_id' => $this->produit_id,
            'critere_id' => $this->critere_id,
            'valeur_id' => $this->valeur_id,
            'valeur' => $this->valeur,
            'defaut' => $this->defaut,
            'structure' => StructureResource::make($this->whenLoaded('structure')),
            'categorie' => LienDisciplineCategorieResource::make($this->whenLoaded('categorie')),
            'discipline' => ListDisciplineResource::make($this->whenLoaded('discipline')),
            'activite' => StructureActiviteResource::make($this->whenLoaded('activite')),
            'produit' => StructureProduitResource::make($this->whenLoaded('produit')),
            'critere' => LienDisciplineCategorieCritereResource::make($this->whenLoaded('critere')),
            'critere_valeur' => LienDisciplineCategorieCritereValeurResource::make($this->whenLoaded('critere_valeur')),
            'valeurs' => LienDisciplineCategorieCritereValeurResource::collection($this->whenLoaded('valeurs')),
            'sous_criteres' => StructureProduitSousCritereResource::collection($this->whenLoaded('sous_criteres')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
