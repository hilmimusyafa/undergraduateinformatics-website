<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\ImportantSection;
use App\Http\Resources\PostResource;
use App\Http\Resources\TagResource;
use App\Http\Resources\SectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class HomePageController extends Controller
{
    public function index(Request $request): View
    {
        $initialData = $this->getHomePageData();

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'EducationalOrganization',
            'name' => $initialData['siteName'],
            'url' => $request->url(),
            'description' => $initialData['description'],
        ];

        return view('app', [
            'title' => $initialData['title'],
            'description' => $initialData['description'],
            'siteName' => $initialData['siteName'],
            'ogTitle' => $initialData['title'],
            'ogDescription' => $initialData['description'],
            'ogImage' => url('/images/banner.jpg'),
            'ogUrl' => $request->url(),
            'jsonLd' => $jsonLd,
            'initialData' => $initialData
        ]);
    }

    public function apiIndex(): JsonResponse
    {
        return response()->json($this->getHomePageData());
    }

    private function getHomePageData(): array
    {
        $latestPost = Post::with('tags')->latest()->first();
        $tags = $latestPost ? $latestPost->tags : Tag::all();
        $posts = Post::with('tags')->latest()->get();
        $sections = ImportantSection::orderBy('order_number')->get();

        return [
            'title' => 'Beranda - Portal Informasi Sarjana Informatika',
            'description' => 'Sumber informasi resmi Program Studi Sarjana Informatika Telkom University yang menyediakan informasi perkuliahan.',
            'siteName' => 'Telkom University',
            'tags' => TagResource::collection($tags)->resolve(),
            'posts' => PostResource::collection($posts)->resolve(),
            'sections' => SectionResource::collection($sections)->resolve()
        ];
    }
}
