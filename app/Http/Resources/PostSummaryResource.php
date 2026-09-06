<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'updated_at' => $this->updated_at?->toIso8601String(),
            'tags' => TagSummaryResource::collection($this->whenLoaded('tags')),
        ];
    }
}
