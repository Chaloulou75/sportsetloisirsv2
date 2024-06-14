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
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'structures' => StructureResource::collection($this->whenLoaded('structures')),
            'structure_activites' => StructureActiviteResource::collection($this->whenLoaded('structure_activites')),
            'posts' => PostResource::collection($this->whenLoaded('posts')),
            'liked_posts' => PostResource::collection($this->whenLoaded('likedPosts')),
            'posts_count' => $this->whenCounted('posts'),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'product_reservations' => ProductReservationResource::collection($this->whenLoaded('productReservations')),
            'customer' => CustomerResource::make($this->whenLoaded('customer')),
            'pivot' => [
                'niveau' => $this->whenPivotLoaded('structure_user', function () {
                    return $this->pivot->niveau;
                }),
                'contact' => $this->whenPivotLoaded('structure_user', function () {
                    return $this->pivot->contact;
                }),
                'email' => $this->whenPivotLoaded('structure_user', function () {
                    return $this->pivot->email;
                }),
                'phone' => $this->whenPivotLoaded('structure_user', function () {
                    return $this->pivot->phone;
                }),
            ],
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];

    }
}
