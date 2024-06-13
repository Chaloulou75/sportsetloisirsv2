<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->when($this->id === $request->user()?->id, $this->email),
            'customer' => CustomerResource::make($this->whenLoaded('customer')),
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'structures' => StructureResource::collection($this->whenLoaded('structures')),
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];

    }
}
