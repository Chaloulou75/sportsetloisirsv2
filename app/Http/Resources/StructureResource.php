<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StructureResource extends JsonResource
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
                    'name' => $this->name,
                    'slug' => $this->slug,
                    'structuretype_id' => $this->structuretype_id,
                    'email' => $this->email,
                    'website' => $this->website,
                    'phone1' => $this->phone1,
                    'phone2' => $this->phone2,
                    'facebook' => $this->facebook,
                    'instagram' => $this->instagram,
                    'youtube' => $this->youtube,
                    'tiktok' => $this->tiktok,
                    'address' => $this->address,
                    'city' => $this->city,
                    'zip_code' => $this->zip_code,
                    'country' => $this->country,
                    'address_lat' => $this->address_lat,
                    'address_lng' => $this->address_lng,
                    'date_creation' => $this->date_creation,
                    'presentation_courte' => $this->presentation_courte,
                    'presentation_longue' => $this->presentation_longue,
                    'abo_news' => $this->abo_news,
                    'abo_promo' => $this->abo_promo,
                    'logo' => $this->logo,
                    'image_url' => $this->image_url,
                    'view_count' => $this->view_count,
                    'creator' => UserResource::make($this->whenLoaded('creator')),
                    'users' => UserResource::collection($this->whenLoaded('users')),
                    'partenaires' => UserResource::collection($this->whenLoaded('partenaires')),
                    'city' => CityResource::make($this->whenLoaded('city')),
                    'cities' => CityResource::collection($this->whenLoaded('cities')),
                    'departement' => DepartementResource::make($this->whenLoaded('departement')),
                    'structuretype' => StructuretypeResource::make($this->whenLoaded('structuretype')),
                    'adresses' => StructureAddressResource::collection($this->whenLoaded('adresses')),
                    'disciplines' => ListDisciplineResource::collection($this->whenLoaded('disciplines')),
                    'categories' => LienDisciplineCategorieResource::collection($this->whenLoaded('categories')),
                    'activites' => StructureActiviteResource::collection($this->whenLoaded('activites')),
                    'produits' => StructureProduitResource::collection($this->whenLoaded('produits')),
                    'plannings' => StructurePlanningResource::collection($this->whenLoaded('plannings')),
                    'reservations' => ProductReservationResource::collection($this->whenLoaded('reservations')),
                    'created_at' => $this->created_at,
                    'updated_at' => $this->updated_at,
                ];

    }
}
