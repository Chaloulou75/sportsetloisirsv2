<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CritereResource extends JsonResource
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
            'nom' => $this->nom,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'discipline_categories' => LienDisciplineCategorieCritereResource::collection($this->whenLoaded('disciplineCategories')),
        ];


    }
}
