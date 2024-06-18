<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategorieResource extends JsonResource
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
            'ico' => $this->ico,
            'type' => $this->type,
            'diffuseur' => $this->diffuseur,
            'derives' => $this->derives,
            'ordre' => $this->ordre,
            'disciplines' => ListDisciplineResource::collection($this->whenLoaded('disciplines')),
            'disc_categories' => LienDisciplineCategorieResource::collection($this->whenLoaded('disc_categories')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
