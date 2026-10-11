<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Support\PageMeta;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PostController extends Controller
{
    public function show(Request $request, string $slugOrId): View|Response
    {
        try {
            $post = Post::with(['tags'])
                ->whereSlugOrId($slugOrId)
                ->firstOrFail();
        } catch (ModelNotFoundException) {
            abort(404);
        }

        return view('PostPage', [
            'title' => $post->title . ' - ' . PageMeta::load()['defaultTitle'],
            'description' => $post->subtitle ?: PageMeta::page('postDetail')['description'],
            'post' => $post,
        ]);
    }
}