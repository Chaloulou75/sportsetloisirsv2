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
            'author' => $this->whenLoaded('author', fn () => UserResource::make($this->author)),
            'disciplines' => $this->whenLoaded('disciplines', fn () => $this->disciplines),
            'tags' => $this->whenLoaded('tags', fn () => $this->tags),
            'comments' => $this->whenLoaded('comments', fn () => $this->comments),
            'comments_count' => $this->comments_count,
            'slug' => $this->slug,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'body' => $this->body,
            'image_url' => $this->image_url,
            'thumbnail' => $this->thumbnail,
            'views_count' => $this->views_count,
            'likes' => $this->likes,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];

    }
}
