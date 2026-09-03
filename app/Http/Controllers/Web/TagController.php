<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Tags\TagsDataService;
use App\Support\PageMeta;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(Request $request): View
    {
        $tagsData = app(TagsDataService::class)->resolve();

        $title = 'Daftar Label - Portal Informasi Sarjana Informatika';
        $description = 'Jelajahi informasi Program Studi Sarjana Informatika Telkom University berdasarkan label.';

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $title,
            'url' => $request->url(),
            'description' => $description,
        ];

        return view('app', PageMeta::viewData($request, $title, $description, $jsonLd, $tagsData));
    }
}
