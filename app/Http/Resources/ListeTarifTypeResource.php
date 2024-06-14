<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListeTarifTypeResource extends JsonResource
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
            'type' => $this->type,
            'slug' => $this->slug,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'tariftypeattributs' => ListeTarifTypeAttributResource::collection($this->whenLoaded('tariftypeattributs')),
            'categories' => LienDisCatTariftypeResource::collection($this->whenLoaded('categories')),
        ];
    }
}
