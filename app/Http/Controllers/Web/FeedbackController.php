<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\FeedbackLink;
use App\Services\MsForms\FormDefinitionService;
use App\Services\MsForms\MsFormsException;
use App\Support\PageMeta;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    public function show(Request $request): View
    {
        $feedbackLink = FeedbackLink::configured()->first();

        $page = PageMeta::page('feedback');

        return view('FeedbackPage', [
            'title' => $page['title'],
            'description' => $page['description'],
            'feedbackLink' => $feedbackLink,
        ]);
    }
}
