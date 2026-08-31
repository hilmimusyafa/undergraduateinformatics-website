<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\ImportantSection;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class HomePageController extends Controller
{
    /**
     * Display the main home portal page
     */
    public function index(): View
    {
        $tags = Tag::with(['posts' => function($query) {
            $query->latest();
        }])->get();

        $posts = Post::with('tags')->latest()->get();
        $sections = ImportantSection::with('important_links')->orderBy('order_number')->get();

        return view('HomePage', compact('tags', 'posts', 'sections'));
    }

    /**
     * Optional API endpoint for mobile / external client integration
     */
    public function apiIndex(): JsonResponse
    {
        $posts = Post::with('tags')->latest()->get();
        $tags = Tag::all();
        $sections = ImportantSection::with('important_links')->orderBy('order_number')->get();

        return response()->json([
            'posts' => $posts,
            'tags' => $tags,
            'sections' => $sections
        ]);
    }
}
