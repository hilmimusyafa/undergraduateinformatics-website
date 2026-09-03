<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(Request $request): View
    {
        $tagsData = $this->getTagsData();

        $title = 'Daftar Label - Portal Informasi Sarjana Informatika';
        $description = 'Jelajahi informasi Program Studi Sarjana Informatika Telkom University berdasarkan label.';

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $title,
            'url' => $request->url(),
            'description' => $description,
        ];

        return view('app', [
            'title' => $title,
            'description' => $description,
            'siteName' => 'Telkom University',
            'ogTitle' => $title,
            'ogDescription' => $description,
            'ogUrl' => $request->url(),
            'jsonLd' => $jsonLd,
            'initialData' => $tagsData,
        ]);
    }

    public function apiIndex(): JsonResponse
    {
        return response()->json($this->getTagsData());
    }

    private function getTagsData(): array
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