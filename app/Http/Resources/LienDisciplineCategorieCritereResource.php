<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LienDisciplineCategorieCritereResource extends JsonResource
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
            'type_champ_form' => $this->type_champ_form,
            'unite' => $this->unite,
            'min' => $this->min,
            'max' => $this->max,
            'discipline_id' => $this->discipline_id,
            'categorie_id' => $this->categorie_id,
            'critere_id' => $this->critere_id,
            'visible_back' => $this->visible_back,
            'visible_front' => $this->visible_front,
            'visible_block' => $this->visible_block,
            'indexable' => $this->indexable,
            'ordre' => $this->ordre,
            'discipline' => ListDisciplineResource::make($this->whenLoaded('discipline')),
            'categorie' => LienDisciplineCategorieResource::make($this->whenLoaded('categorie')),
            'critere' => CritereResource::make($this->whenLoaded('critere')),
            'valeurs' => LienDisciplineCategorieCritereValeurResource::collection($this->whenLoaded('valeurs')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
