<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductReservationResource extends JsonResource
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
            'session_id' => $this->session_id,
            'user_id' => $this->user_id,
            'discipline_id' => $this->discipline_id,
            'categorie_id' => $this->categorie_id,
            'structure_id' => $this->structure_id,
            'activite_id' => $this->activite_id,
            'activite_title' => $this->activite_title,
            'produit_id' => $this->produit_id,
            'produit_criteres' => $this->produit_criteres,
            'cat_tarif_id' => $this->cat_tarif_id,
            'tarif_title' => $this->tarif_title,
            'tarif_amount' => $this->tarif_amount,
            'quantity' => $this->quantity,
            'paid' => $this->paid,
            'stripe_session_id' => $this->stripe_session_id,
            'customer_id' => $this->customer_id,
            'paiement_datetime' => $this->paiement_datetime,
            'paiement_method' => $this->paiement_method,
            'transaction_number' => $this->transaction_number,
            'client_confirmed' => $this->client_confirmed,
            'datetime_client_confirmed' => $this->datetime_client_confirmed,
            'client_cancelled' => $this->client_cancelled,
            'datetime_client_cancelled' => $this->datetime_client_cancelled,
            'pending' => $this->pending,
            'confirmed' => $this->confirmed,
            'datetime_structure_confirmed' => $this->datetime_structure_confirmed,
            'finished' => $this->finished,
            'datetime_structure_finished' => $this->datetime_structure_finished,
            'cancelled' => $this->cancelled,
            'datetime_structure_cancelled' => $this->datetime_structure_cancelled,
            'code' => $this->code,
            'code_confirmed' => $this->code_confirmed,
            'datetime_code_confirmed' => $this->datetime_code_confirmed,
            'user' => UserResource::make($this->whenLoaded('user')),
            'customer' => CustomerResource::make($this->whenLoaded('customer')),
            'discipline' => ListDisciplineResource::make($this->whenLoaded('discipline')),
            'categorie' => LienDisciplineCategorieResource::make($this->whenLoaded('categorie')),
            'structure' => StructureResource::make($this->whenLoaded('structure')),
            'activite' => StructureActiviteResource::make($this->whenLoaded('activite')),
            'produit' => StructureProduitResource::make($this->whenLoaded('produit')),
            'cat_tarif' => StructureCatTarifResource::make($this->whenLoaded('cat_tarif')),
            'attributs' => ReservationAttributResource::collection($this->whenLoaded('attributs')),
            'sous_attributs' => ReservationSousAttributResource::collection($this->whenLoaded('sous_attributs')),
            'plannings' => StructurePlanningResource::collection($this->whenLoaded('plannings')),
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
