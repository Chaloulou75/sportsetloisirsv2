<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LienDisCatTarAttrValeurResource extends JsonResource
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
            'valeur' => $this->valeur,
            'ordre' => $this->ordre,
            'attribut' => LienDisCatTartypAttributResource::make($this->whenLoaded('attribut')),
            'sous_attributs' => LienDisCatTarAttrSousAttrResource::collection($this->whenLoaded('sous_attributs')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
