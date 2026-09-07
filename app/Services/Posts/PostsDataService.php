<?php

namespace App\Services\Posts;

use App\Http\Resources\PostResource;
use App\Models\Post;

class PostsDataService
{
    public function resolveDetail(string $slugOrId): array
    {
        $post = Post::with('tags')->whereSlugOrId($slugOrId)->firstOrFail();

        return [
            'status' => 'success',
            'data' => PostResource::make($post)->resolve(),
        ];
    }
}