<?php

namespace App\Http\Controllers;

use App\Models\FeedbackLink;
use App\Services\MsForms\FormDefinitionService;
use App\Services\MsForms\MsFormsException;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    public function show(Request $request): View
    {
        $feedbackLink = FeedbackLink::configured()->first();

        $initialData = null;

        if ($feedbackLink) {
            try {
                $initialData = app(FormDefinitionService::class)->resolve($feedbackLink);
            } catch (MsFormsException) {
                $initialData = null;
            }
        }

        if ($initialData === null) {
            $initialData = ['link' => $feedbackLink?->link];
        }

        $title = 'Masukan - Portal Informasi Sarjana Informatika';
        $description = 'Sampaikan masukan Anda melalui formulir umpan balik Program Studi Sarjana Informatika Telkom University.';

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $title,
            'url' => $request->url(),
            'description' => $description,
        ];

        return view('app', [
            'title' => $title,
            'description' => $description,
            'ogUrl' => $request->url(),
            'jsonLd' => $jsonLd,
            'initialData' => [
                'status' => 'success',
                'data' => $initialData,
            ],
        ]);
    }
}
