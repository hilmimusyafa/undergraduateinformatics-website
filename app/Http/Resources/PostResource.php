<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'body' => $this->body,
            'image' => $this->image,
            'tags' => TagSummaryResource::collection($this->whenLoaded('tags')),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
