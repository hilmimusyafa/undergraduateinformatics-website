<?php

namespace App\Services\Home;

use App\Http\Resources\DashboardDatasetResource;
use App\Http\Resources\ImportantLinkResource;
use App\Http\Resources\PostSummaryResource;
use App\Models\DashboardDataset;
use App\Models\ImportantLink;
use App\Models\Post;

class HomeDataService
{
    public function resolve(): array
    {
        $latestPosts = Post::query()
            ->with('tags')
            ->latest()
            ->limit(5)
            ->get();

        $latestLinks = ImportantLink::query()
            ->with('important_section')
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $dashboard = DashboardDatasetResource::collection(
            DashboardDataset::query()->with('items')->orderBy('id')->get()
        )->resolve();

        return [
            'status' => 'success',
            'data' => [
                'latest_posts' => PostSummaryResource::collection($latestPosts)->resolve(),
                'latest_links' => ImportantLinkResource::collection($latestLinks)->resolve(),
                'dashboard' => $dashboard,
            ],
        ];
    }
}
