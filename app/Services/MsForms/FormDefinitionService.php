<?php

namespace App\Services\MsForms;

use App\Models\FeedbackLink;
use Illuminate\Support\Facades\Cache;

final class FormDefinitionService
{
    private const CACHE_TTL = 15;

    public function resolve(FeedbackLink $feedbackLink): array
    {
        return Cache::remember(
            $this->cacheKey($feedbackLink),
            now()->addMinutes(self::CACHE_TTL),
            fn () => $this->fetch($feedbackLink)
        );
    }

    public function refresh(FeedbackLink $feedbackLink): array
    {
        $data = $this->fetch($feedbackLink);

        Cache::put($this->cacheKey($feedbackLink), $data, now()->addMinutes(self::CACHE_TTL));

        return $data;
    }

    private function fetch(FeedbackLink $feedbackLink): array
    {
        $client = app(MsFormsClient::class);
        $target = $client->resolve($feedbackLink->link);
        $raw = $client->fetchFormDefinition($target);
        $normalized = (new FormDefinitionNormalizer())->normalize($raw);

        return array_merge(['link' => $feedbackLink->link], $normalized);
    }

    private function cacheKey(FeedbackLink $feedbackLink): string
    {
        return 'msforms-definition:' . md5($feedbackLink->link);
    }
}