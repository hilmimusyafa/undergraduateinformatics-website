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

        $page = PageMeta::page('links');

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $page['title'],
            'url' => $request->url(),
            'description' => $page['description'],
        ];

        return view('app', PageMeta::viewData($request, 'links', $jsonLd, $linksData));
    }
}
