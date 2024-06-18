<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StructureCatTarAttSousAttrResource extends JsonResource
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
            'valeur' => $this->valeur,
            'str_cat_tar_att_id' => $this->str_cat_tar_att_id,
            'sousattribut_id' => $this->sousattribut_id,
            'ss_att_valeur_id' => $this->ss_att_valeur_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'cat_tar_attribut' =>  StructureCatTarAttributResource::make($this->whenLoaded('cat_tar_attribut')),
            'sous_attribut' => LienDisCatTarAttrSousAttrResource::make($this->whenLoaded('sous_attribut')),
            'sous_attribut_valeur' => LienDisCatTarAttrSousAttrValeurResource::make($this->whenLoaded('sous_attribut_valeur')),
        ];
    }
}
