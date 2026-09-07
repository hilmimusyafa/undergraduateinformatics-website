<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Tags\TagsDataService;
use App\Support\PageMeta;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(Request $request): View
    {
        $tagsData = app(TagsDataService::class)->resolve();

        $page = PageMeta::page('tagList');

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $page['title'],
            'url' => $request->url(),
            'description' => $page['description'],
        ];

        return view('app', PageMeta::viewData($request, 'tagList', $jsonLd, $tagsData));
    }

    public function show(Request $request, string $slugOrId): View|Response
    {
        try {
            $tagData = app(TagsDataService::class)->resolveDetail($slugOrId);
        } catch (ModelNotFoundException) {
            return response()->view(
                'app',
                PageMeta::viewData($request, 'notFound', [], ['notFound' => true], null, null),
                404
            );
        }

        $tag = $tagData['data'];

        $title = $tag['name'] . ' - Portal Informasi Sarjana Informatika';
        $description = $tag['description'] ?? '';

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $title,
            'url' => $request->url(),
            'description' => $description,
        ];

        return view('app', PageMeta::viewData($request, 'tagDetail', $jsonLd, $tagData, $title, $description));
    }
}
