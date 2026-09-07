<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Posts\PostsDataService;
use App\Support\PageMeta;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PostController extends Controller
{
    public function show(Request $request, string $slugOrId): View|Response
    {
        try {
            $postData = app(PostsDataService::class)->resolveDetail($slugOrId);
        } catch (ModelNotFoundException) {
            return response()->view(
                'app',
                PageMeta::viewData($request, 'notFound', [], ['notFound' => true], null, null),
                404
            );
        }

        $post = $postData['data'];

        $title = $post['title'] . ' - Portal Informasi Sarjana Informatika';
        $description = $post['subtitle'] ?? '';
        $siteName = PageMeta::load()['siteName'];
        $ogImage = $post['image'] ? url($post['image']) : null;

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post['title'],
            'url' => $request->url(),
            'image' => $ogImage,
            'publisher' => [
                '@type' => 'Organization',
                'name' => $siteName,
            ],
            'datePublished' => $post['created_at'],
            'dateModified' => $post['updated_at'],
        ];

        return view('app', PageMeta::viewData($request, 'postDetail', $jsonLd, $postData, $title, $description, $ogImage));
    }
}