<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'file_name' => $this->file_name,
            'mime_type' => $this->mime_type,
            'size' => $this->size,
            'club' => [
                'id' => $this->club->id,
                'name' => $this->club->name,
            ],
            'created_at' => $this->created_at->format('d/m/Y'),
            'preview_url' => $this->preview_url,
            'url' => $this->url,
        ];
    }
}
