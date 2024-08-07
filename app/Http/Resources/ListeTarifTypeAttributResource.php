<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListeTarifTypeAttributResource extends JsonResource
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
            'type_id' => $this->type_id,
            'attribut' => $this->attribut,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'tarif_type' => ListeTarifTypeResource::make($this->whenLoaded('tarifType')),
        ];

    }
}
