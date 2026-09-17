<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;
use App\Support\PageMeta;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $tagIds = $request->query('tags', []);
        $tagIds = is_array($tagIds) ? array_map('intval', $tagIds) : [];

        $tags = Tag::query()->orderBy('name')->get();

        $query = Post::query()->with('tags')->orderByDesc('updated_at');

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', '%' . $search . '%')
                    ->orWhere('subtitle', 'like', '%' . $search . '%')
                    ->orWhere('body', 'like', '%' . $search . '%');
            });
        }

        if (! empty($tagIds)) {
            $query->whereHas('tags', function ($builder) use ($tagIds) {
                $builder->whereIn('tags.id', $tagIds);
            });
        }

        $postsSearch = $query->get();
        $tagsSearch = $tags->whereIn('id', $tagIds);

        return view('SearchPage', [
            'title' => PageMeta::page('postSearch')['title'],
            'description' => PageMeta::page('postSearch')['description'],
            'search' => $search,
            'tags' => $tags,
            'tags_search' => $tagsSearch,
            'posts_search' => $postsSearch,
        ]);
    }
}
