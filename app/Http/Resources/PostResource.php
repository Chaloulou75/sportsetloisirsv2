<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'slug' => $this->slug,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'body' => $this->body,
            'image_url' => $this->image_url,
            'thumbnail' => $this->thumbnail,
            'views_count' => $this->views_count,
            'likes' => $this->likes,
            'likes_count' => $this->whenCounted('likes'),
            'author' => UserResource::make($this->whenLoaded('author')),
            'disciplines' => ListDisciplineResource::collection($this->whenLoaded('disciplines')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'comments_count' => $this->whenCounted('comments'),
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];

    }
}
