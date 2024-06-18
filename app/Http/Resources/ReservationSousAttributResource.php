<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationSousAttributResource extends JsonResource
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
            'reservation_id' => $this->reservation_id,
            'reservation_attribut_id' => $this->reservation_attribut_id,
            'booking_field_ss_field_id' => $this->booking_field_ss_field_id,
            'booking_ss_field_valeur_id' => $this->booking_ss_field_valeur_id,
            'valeur' => $this->valeur,
            'reservation' => ProductReservationResource::make($this->whenLoaded('reservation')),
            'reservation_attribut' => ReservationAttributResource::make($this->whenLoaded('reservation_attribut')),
            'booking_sous_field' => LienDisCatTarBookingFieldSousFieldResource::make($this->whenLoaded('booking_sous_field')),
            'booking_sous_field_valeur' => LienDisCatTarBookingFieldSsFieldValeurResource::make($this->whenLoaded('booking_sous_field_valeur')),
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];

    }
}
