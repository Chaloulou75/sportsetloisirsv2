<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'prenom' => $this->prenom,
            'user' => UserResource::make($this->whenLoaded('user')),
            'reservations' => ProductReservationResource::make($this->whenLoaded('reservations')),
            'adresse' => $this->adresse,
            'zip_code' => $this->zip_code,
            'country' => $this->country,
            'phone' => $this->phone,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];

    }
}
