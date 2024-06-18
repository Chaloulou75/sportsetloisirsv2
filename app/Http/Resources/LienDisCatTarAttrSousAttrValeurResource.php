<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LienDisCatTarAttrSousAttrValeurResource extends JsonResource
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
            'sousattribut_id' => $this->sousattribut_id,
            'valeur' => $this->valeur,
            'ordre' => $this->ordre,
            'inclus_all' => $this->inclus_all,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'sous_attribut' => LienDisCatTarAttrSousAttrResource::make($this->whenLoaded('sous_attribut')),
        ];
    }
}
