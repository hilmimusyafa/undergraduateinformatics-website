<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Home\HomeDataService;
use App\Support\PageMeta;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $initialData = app(HomeDataService::class)->resolve();

        $seo = PageMeta::page('home');
        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'EducationalOrganization',
            'name' => PageMeta::load()['siteName'],
            'url' => $request->url(),
            'description' => $seo['description'],
        ];

        return view('app', PageMeta::viewData($request, 'home', $jsonLd, $initialData));
    }
}
