<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ImportantSection;
use App\Support\PageMeta;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LinkController extends Controller
{
    public function index(Request $request): View
    {
        $sections = ImportantSection::query()
            ->with(['important_links' => function ($query) {
                $query->orderByDesc('updated_at')->orderByDesc('id');
            }])
            ->orderBy('order_number')
            ->get();

        return view('LinkPentingPage', [
            'title' => PageMeta::page('links')['title'],
            'description' => PageMeta::page('links')['description'],
            'sections' => $sections,
        ]);
    }
}
