<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StructureCatTarAttributResource extends JsonResource
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
            'str_cat_tar_id' => $this->str_cat_tar_id,
            'cat_tar_att_id' => $this->cat_tar_att_id,
            'dis_cat_tar_att_valeur_id' => $this->dis_cat_tar_att_valeur_id,
            'cat_tarif' => StructureCatTarifResource::make($this->whenLoaded('cat_tarif')),
            'tarif_attribut' => LienDisCatTartypAttributResource::make($this->whenLoaded('tarif_attribut')),
            'sous_attributs' => StructureCatTarAttSousAttrResource::collection($this->whenLoaded('sous_attributs')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
