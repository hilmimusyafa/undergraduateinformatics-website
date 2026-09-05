<?php

namespace App\Support;

use Illuminate\Http\Request;

final class PageMeta
{
    public static function viewData(
        Request $request,
        string $title,
        string $description,
        array $jsonLd,
        array $initialData,
        ?string $ogImage = null
    ): array {
        return [
            'title' => $title,
            'description' => $description,
            'siteName' => 'Telkom University',
            'ogTitle' => $title,
            'ogDescription' => $description,
            'ogImage' => $ogImage ?? url('/images/banner.jpg'),
            'ogUrl' => $request->url(),
            'jsonLd' => $jsonLd,
            'initialData' => $initialData,
        ];
    }
}
