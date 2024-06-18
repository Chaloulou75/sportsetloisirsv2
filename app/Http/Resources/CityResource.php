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
            'region' => $this->region,
            'nom_region' => $this->nom_region,
            'departement' => $this->departement,
            'nom_departement' => $this->nom_departement,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'tolerance_rayon' => $this->tolerance_rayon,
            'view_count' => $this->view_count,
            'city_departement' => DepartementResource::make($this->whenLoaded('city_departement')),
            'structures' => StructureResource::collection($this->whenLoaded('structures')),
            'structures_count' => $this->whenCounted('structures'),
            'adresses' => StructureAddressResource::collection($this->whenLoaded('adresses')),
            'produits' => StructureProduitResource::collection($this->whenLoaded('produits')),
            'produits_count' => $this->whenCounted('produits'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
