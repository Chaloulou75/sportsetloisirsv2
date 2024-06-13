<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LienDisciplineCategorieCritereValeurResource extends JsonResource
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
            'discipline_categorie_critere_id' => $this->discipline_categorie_critere_id,
            'valeur' => $this->valeur,
            'ordre' => $this->ordre,
            // 'sous_criteres' => SousCritereResource::collection($this->whenLoaded('sous_criteres')),
            // 'sous_criteres_valeurs' => SousCritereValeurResource::collection($this->whenLoaded('sous_criteres_valeurs')),
        ];
    }
}
