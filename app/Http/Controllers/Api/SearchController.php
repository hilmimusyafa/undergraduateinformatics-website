<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Search\SearchDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $page = max((int) $request->query('page', 1), 1);
        $perPage = min(max((int) $request->query('per_page', 10), 1), 50);

        $q = $request->query('q');
        $q = is_string($q) ? $q : null;

        return response()->json(
            app(SearchDataService::class)->resolve($q, $page, $perPage)
        );
    }
}
