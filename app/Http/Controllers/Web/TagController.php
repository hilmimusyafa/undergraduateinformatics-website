<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Support\PageMeta;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(Request $request): View
    {
        $tags = Tag::withCount('posts')
            ->withMax('posts', 'updated_at')
            ->orderByDesc('posts_max_updated_at')
            ->orderBy('name')
            ->get();

        return view('TagPage', [
            'title' => PageMeta::page('tagList')['title'],
            'description' => PageMeta::page('tagList')['description'],
            'tags' => $tags,
            'tag' => null,
        ]);
    }

    public function show(Request $request, string $slugOrId): View|Response
    {
        try {
            $tag = Tag::with([
                'posts' => fn ($query) => $query->orderByDesc('updated_at'),
                'posts.tags',
            ])->whereSlugOrId($slugOrId)->firstOrFail();
        } catch (ModelNotFoundException) {
            abort(404);
        }

        return view('TagPage', [
            'title' => $tag->name . ' - ' . PageMeta::load()['defaultTitle'],
            'description' => $tag->description ?: PageMeta::page('tagDetail')['description'],
            'tag' => $tag,
            'tags' => collect(),
        ]);
    }
}
