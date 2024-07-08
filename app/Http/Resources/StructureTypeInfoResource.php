<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StructureTypeInfoResource extends JsonResource
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
            'attribut_id' => $this->attribut_id,
            'valeur_id' => $this->valeur_id,
            'valeur' => $this->valeur,
            'structure' => StructureResource::make($this->whenLoaded('structure')),
            'attribut' => StructureTypeAttributResource::make($this->whenLoaded('attribut')),
            'attribut_valeur' => StructureTypeValeurResource::make($this->whenLoaded('attribut_valeur')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
