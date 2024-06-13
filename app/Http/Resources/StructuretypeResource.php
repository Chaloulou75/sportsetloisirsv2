<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StructuretypeResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'structures' => StructureResource::collection($this->whenLoaded('structures')),
            'structuretypeattributs' => StructureTypeAttributResource::collection($this->whenLoaded('structuretypeattributs')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
