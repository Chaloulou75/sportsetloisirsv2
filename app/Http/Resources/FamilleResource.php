<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FamilleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'disciplines' => $this->whenLoaded('disciplines', fn () => $this->disciplines),
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
