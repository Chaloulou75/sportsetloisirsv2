<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StructureTypeAttributResource extends JsonResource
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
            'structuretype_id' => $this->structuretype_id,
            'nom' => $this->nom,
            'type_champ_form' => $this->type_champ_form,
            'structuretype' => StructuretypeResource::make($this->whenLoaded('structuretype')),
            'valeurs' => StructureTypeValeurResource::collection($this->whenLoaded('valeurs')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
