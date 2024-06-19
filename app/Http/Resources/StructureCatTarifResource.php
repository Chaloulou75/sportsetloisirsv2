<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StructureCatTarifResource extends JsonResource
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
            'titre' => $this->titre,
            'description' => $this->description,
            'amount' => $this->amount,
            'structure_id' => $this->structure_id,
            'categorie_id' => $this->categorie_id,
            'dis_cat_tar_typ_id' => $this->dis_cat_tar_typ_id,
            'structure' => StructureResource::make($this->whenLoaded('structure')),
            'categorie' => LienDisciplineCategorieResource::make($this->whenLoaded('categorie')),
            'cat_tarif_type' => LienDisCatTariftypeResource::make($this->whenLoaded('cat_tarif_type')),
            'attributs' => StructureCatTarAttributResource::collection($this->whenLoaded('attributs')),
            'produits' => StructureProduitResource::collection($this->whenLoaded('produits')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
