<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Tag;

class HomePageController extends Controller
{
    /**
     * Return home page view with tags and posts
     */
    public function index()
    {
        // Fetch tags and latest posts data
        $check = Post::with('tags')->latest()->first();

        if ($check) {
            // Jika sudah ada post
            $tags = $check->tags;
        } else {
            // Jika belum ada post
            $tags = Tag::all();
        }
        
        $posts = Post::latest()->get();

        // Return home page view with data
        return view('HomePage', [
            'tags' => $tags,
            'posts' => $posts
        ]);
    }
}
