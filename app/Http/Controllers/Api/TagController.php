<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Tags\TagsDataService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(app(TagsDataService::class)->resolve());
    }

    public function show(string $slugOrId): JsonResponse
    {
        try {
            $data = app(TagsDataService::class)->resolveDetail($slugOrId);
        } catch (ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tag not found',
            ], 404);
        }

        return response()->json($data);
    }
}
