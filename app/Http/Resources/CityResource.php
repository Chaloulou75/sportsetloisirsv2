<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
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
            'code_postal' => $this->code_postal,
            'ville' => $this->ville,
            'ville_formatee' => $this->ville_formatee,
            'nom_departement' => $this->nom_departement,
            'view_count' => $this->view_count,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'tolerance_rayon' => $this->tolerance_rayon,
            'city_departement' => DepartementResource::make($this->whenLoaded('city_departement')),
            'structures' => StructureResource::collection($this->whenLoaded('structures')),
            'adresses' => StructureAddressResource::collection($this->whenLoaded('adresses')),
            'produits' => StructureProduitResource::collection($this->whenLoaded('produits')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
