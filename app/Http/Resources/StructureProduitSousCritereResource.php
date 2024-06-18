<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StructureProduitSousCritereResource extends JsonResource
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
            'activite_id' => $this->activite_id,
            'produit_id' => $this->produit_id,
            'critere_id' => $this->critere_id,
            'prod_crit_id' => $this->prod_crit_id,
            'critere_valeur_id' => $this->critere_valeur_id,
            'sous_critere_id' => $this->sous_critere_id,
            'sous_critere_valeur_id' => $this->sous_critere_valeur_id,
            'valeur' => $this->valeur,
            'activite' => StructureActiviteResource::make($this->whenLoaded('activite')),
            'produit' =>  StructureProduitResource::make($this->whenLoaded('produit')),
            'produit_critere' =>  StructureProduitCritereResource::make($this->whenLoaded('produit_critere')),
            'critere' =>  LienDisciplineCategorieCritereResource::make($this->whenLoaded('critere')),
            'critere_valeur' =>  LienDisciplineCategorieCritereValeurResource::make($this->whenLoaded('critere_valeur')),
            'sous_critere' =>  LiensDisCatCritValSsCritResource::make($this->whenLoaded('sous_critere')),
            'sous_critere_valeur' =>  LiensDisCatCritValSsCritValeurResource::make($this->whenLoaded('sous_critere_valeur')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
