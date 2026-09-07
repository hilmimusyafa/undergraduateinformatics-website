<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Search\SearchDataService;
use App\Support\PageMeta;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $page = max((int) $request->query('page', 1), 1);
        $perPage = min(max((int) $request->query('per_page', 10), 1), 50);

        $q = $request->query('q');
        $q = is_string($q) ? $q : null;

        $searchData = app(SearchDataService::class)->resolve($q, $page, $perPage);

        $seo = PageMeta::page('postSearch');
        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'SearchResultsPage',
            'name' => $seo['title'],
            'url' => $request->url(),
            'description' => $seo['description'],
        ];

        return view('app', PageMeta::viewData($request, 'postSearch', $jsonLd, $searchData));
    }
}
