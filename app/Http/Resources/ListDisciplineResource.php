<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListDisciplineResource extends JsonResource
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
            'theme' => $this->theme,
            'description' => $this->description,
            'view_count' => $this->view_count,
            'familles' => FamilleResource::collection($this->whenLoaded('familles')),
            'categories' => CategorieResource::collection($this->whenLoaded('categories')),
            'structures' => StructureResource::collection($this->whenLoaded('structures')),
            'str_categories' => StructureCategorieResource::collection($this->whenLoaded('str_categories')),
            'activites' => StructureActiviteResource::collection($this->whenLoaded('activites')),
            'structureProduits' => StructureProduitResource::collection($this->whenLoaded('structureProduits')),
            'disciplinesSimilaires' => ListDisciplineResource::collection($this->whenLoaded('disciplinesSimilaires')),
            'posts' => PostResource::collection($this->whenLoaded('posts')),
            'reservations' => ProductReservationResource::collection($this->whenLoaded('reservations')),
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];

    }
}
