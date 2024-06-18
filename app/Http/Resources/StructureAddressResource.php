<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StructureAddressResource extends JsonResource
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
            'structure_id' => $this->structure_id,
            'address' => $this->address,
            'zip_code' => $this->zip_code,
            'city_id' => $this->city_id,
            'city_name' => $this->city,
            'country_id' => $this->country_id,
            'country' => $this->country,
            'address_lat' => $this->address_lat,
            'address_lng' => $this->address_lng,
            'phone' => $this->phone,
            'email' => $this->email,
            'name' => $this->name,
            'structure' => StructureResource::make($this->whenLoaded('structure')),
            'city' =>  CityResource::make($this->whenLoaded('city')),
            'departement' => DepartementResource::make($this->whenLoaded('departement')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
