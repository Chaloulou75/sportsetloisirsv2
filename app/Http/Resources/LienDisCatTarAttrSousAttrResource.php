<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LienDisCatTarAttrSousAttrResource extends JsonResource
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
            'cat_tar_att_id' => $this->cat_tar_att_id,
            'att_valeur_id' => $this->att_valeur_id,
            'nom' => $this->nom,
            'type_champ_form' => $this->type_champ_form,
            'type_champ_id' => $this->type_champ_id,
            'ordre' => $this->ordre,
            'attribut' => LienDisCatTartypAttributResource::make($this->whenLoaded('attribut')),
            'attribut_valeur' => LienDisCatTarAttrValeurResource::make($this->whenLoaded('attribut_valeur')),
            'type_champ' => TypeChampResource::make($this->whenLoaded('type_champ')),
            'valeurs' => LienDisCatTarAttrSousAttrValeurResource::collection($this->whenLoaded('valeurs')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
