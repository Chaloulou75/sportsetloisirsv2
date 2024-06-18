<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationAttributResource extends JsonResource
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
            'valeur' => $this->valeur,
            'reservation_id' => $this->reservation_id,
            'booking_field_id' => $this->booking_field_id,
            'booking_field_valeur_id' => $this->booking_field_valeur_id,
            'booking_field' => LienDisCatTarBookingFieldResource::make($this->whenLoaded('booking_field')),
            'reservation' => ProductReservationResource::make($this->whenLoaded('reservation')),
            'reservation_sous_attributs' => ReservationSousAttributResource::collection($this->whenLoaded('reservation_sous_attributs')),
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];

    }
}
