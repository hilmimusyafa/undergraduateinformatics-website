<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Posts\PostsDataService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function show(string $slugOrId): JsonResponse
    {
        try {
            $data = app(PostsDataService::class)->resolveDetail($slugOrId);
        } catch (ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Post not found',
            ], 404);
        }

        return response()->json($data);
    }
}