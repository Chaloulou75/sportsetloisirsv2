<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LiensDisCatCritValSsCritResource extends JsonResource
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
            'dis_cat_crit_val_id' => $this->dis_cat_crit_val_id,
            'nom' => $this->nom,
            'type_champ_form' => $this->type_champ_form,
            'type_champ_id' => $this->type_champ_id,
            'unite' => $this->unite,
            'min' => $this->min,
            'max' => $this->max,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'type_champ' => TypeChampResource::make($this->whenLoaded('type_champ')),
            'critere_valeur' => LienDisciplineCategorieCritereValeurResource::make($this->whenLoaded('critere_valeur')),
            'sous_criteres_valeurs' => LiensDisCatCritValSsCritValeurResource::collection($this->whenLoaded('sous_criteres_valeurs')),
            'prod_sous_crit_valeurs' => StructureProduitSousCritereResource::collection($this->whenLoaded('prod_sous_crit_valeurs')),
        ];

    }
}
