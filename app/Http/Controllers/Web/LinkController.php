<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Links\LinksDataService;
use App\Support\PageMeta;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LinkController extends Controller
{
    public function index(Request $request): View
    {
        $linksData = app(LinksDataService::class)->getSectionsWithLinks();

        $title = 'Tautan Penting - Portal Informasi Sarjana Informatika';
        $description = 'Kumpulan tautan penting terkait informasi di Program Studi Sarjana Informatika Telkom University.';

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $title,
            'url' => $request->url(),
            'description' => $description,
        ];

        return view('app', PageMeta::viewData($request, $title, $description, $jsonLd, $linksData));
    }
}
