<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StructureTypeValeurResource extends JsonResource
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
            'id_champ' => $this->id_champ,
            'nom' => $this->nom,
            'structuretype_attribut' => StructureTypeAttributResource::make($this->whenLoaded('structuretype_attribut')),
            'structuretype_infos' => StructureTypeInfoResource::collection($this->whenLoaded('structuretype_infos')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
