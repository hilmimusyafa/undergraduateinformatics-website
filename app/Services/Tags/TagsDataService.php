<?php

namespace App\Services\Tags;

use App\Http\Resources\TagResource;
use App\Models\Tag;

class TagsDataService
{
    public function resolve(): array
    {
        $tags = Tag::withCount('posts')
            ->withMax('posts', 'updated_at')
            ->orderByDesc('posts_max_updated_at')
            ->orderBy('name')
            ->get();

        return [
            'status' => 'success',
            'data' => TagResource::collection($tags)->resolve(),
        ];
    }
}
