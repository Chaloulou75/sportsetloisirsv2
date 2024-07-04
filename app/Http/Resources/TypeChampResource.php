<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TypeChampResource extends JsonResource
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
            'type' => $this->type,
            'criterable' => $this->criterable,
            'sous_criterable' => $this->sous_criterable,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
