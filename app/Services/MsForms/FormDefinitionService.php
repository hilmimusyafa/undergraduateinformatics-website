<?php

namespace App\Services\MsForms;

use App\Models\FeedbackLink;
use Illuminate\Support\Facades\Cache;

final class FormDefinitionService
{
    public function resolve(FeedbackLink $feedbackLink): array
    {
        return Cache::remember(
            'msforms-definition:' . md5($feedbackLink->link),
            now()->addMinutes(15),
            function () use ($feedbackLink) {
                $client = app(MsFormsClient::class);
                $target = $client->resolve($feedbackLink->link);
                $raw = $client->fetchFormDefinition($target);
                $normalized = (new FormDefinitionNormalizer())->normalize($raw);

                return array_merge(['link' => $feedbackLink->link], $normalized);
            }
        );
    }
}
