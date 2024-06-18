<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LienDisciplineCategorieCritereValeurResource extends JsonResource
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
            'discipline_categorie_critere_id' => $this->discipline_categorie_critere_id,
            'valeur' => $this->valeur,
            'ordre' => $this->ordre,
            'defaut' => $this->defaut,
            'inclus_all' => $this->inclus_all,
            'critere' => LienDisciplineCategorieCritereResource::make($this->whenLoaded('critere')),
            'sous_criteres' => LiensDisCatCritValSsCritResource::collection($this->whenLoaded('sous_criteres')),
            'produit_sous_criteres' => StructureProduitSousCritereResource::collection($this->whenLoaded('produit_sous_criteres')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
