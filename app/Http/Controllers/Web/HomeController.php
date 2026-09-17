<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\DashboardDataset;
use App\Models\Post;
use App\Models\Tag;
use App\Support\PageMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $seo = PageMeta::page('home');

        $tags = Tag::query()
            ->with(['posts' => function ($query) {
                $query->orderByDesc('updated_at')->limit(3);
            }, 'posts.tags'])
            ->withCount('posts')
            ->orderBy('name')
            ->get();

        $posts = Post::query()
            ->with('tags')
            ->latest()
            ->limit(10)
            ->get();

        $statistics = collect();
        if (Schema::hasTable('dashboard_datasets') && Schema::hasTable('dashboard_dataset_items')) {
            $statistics = DashboardDataset::query()
                ->with(['items' => fn ($query) => $query->orderBy('sort_order')])
                ->where('sheet_name', 'Mahasiswa')
                ->orderBy('id')
                ->get();
        }

        $statisticsPayload = $statistics->map(function ($dataset) {
            return [
                'id' => $dataset->id,
                'chart_type' => $dataset->chart_type,
                'labels' => $dataset->items->pluck('label')->values(),
                'values' => $dataset->items->pluck('value')->values(),
            ];
        })->values();

        return view('HomePage', [
            'title' => $seo['title'],
            'description' => $seo['description'],
            'tags' => $tags,
            'posts' => $posts,
            'statistics' => $statistics,
            'statisticsPayload' => $statisticsPayload,
        ]);
    }
}
