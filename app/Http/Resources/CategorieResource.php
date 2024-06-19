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
            'pivot' => [
                'id' => $this->whenPivotLoaded('liens_disciplines_categories', function () {
                    return $this->pivot->id;
                }),
                'slug' => $this->whenPivotLoaded('liens_disciplines_categories', function () {
                    return $this->pivot->slug;
                }),
                'discipline_id' => $this->whenPivotLoaded('liens_disciplines_categories', function () {
                    return $this->pivot->discipline_id;
                }),
                'categorie_id' => $this->whenPivotLoaded('liens_disciplines_categories', function () {
                    return $this->pivot->categorie_id;
                }),
                'nom_categorie_pro' => $this->whenPivotLoaded('liens_disciplines_categories', function () {
                    return $this->pivot->nom_categorie_pro;
                }),
                'nom_categorie_client' => $this->whenPivotLoaded('liens_disciplines_categories', function () {
                    return $this->pivot->nom_categorie_client;
                }),
                'created_at' => $this->whenPivotLoaded('liens_disciplines_categories', function () {
                    return $this->pivot->created_at;
                }),
                'updated_at' => $this->whenPivotLoaded('liens_disciplines_categories', function () {
                    return $this->pivot->updated_at;
                }),
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
