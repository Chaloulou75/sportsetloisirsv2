<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartementResource extends JsonResource
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
            'slug' => $this->slug,
            'departement' => $this->departement,
            'numero' => $this->numero,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'prefixe' => $this->prefixe,
            'view_count' => $this->view_count,
            'cities' => CityResource::collection($this->whenLoaded('cities')),
            'structures' => StructureResource::collection($this->whenLoaded('structures')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
