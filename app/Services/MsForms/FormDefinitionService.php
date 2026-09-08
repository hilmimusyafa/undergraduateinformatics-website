<?php

namespace App\Services\MsForms;

use Illuminate\Support\Facades\Cache;

final class FormDefinitionService
{
    private const CACHE_TTL = 15;

    public function resolve(string $link): array
    {
        return Cache::remember(
            $this->cacheKey($link),
            now()->addMinutes(self::CACHE_TTL),
            fn () => $this->fetch($link)
        );
    }

    public function refresh(string $link): array
    {
        $data = $this->fetch($link);

        Cache::put($this->cacheKey($link), $data, now()->addMinutes(self::CACHE_TTL));

        return $data;
    }

    private function fetch(string $link): array
    {
        $client = app(MsFormsClient::class);
        $target = $client->resolve($link);
        $raw = $client->fetchFormDefinition($target);
        $normalized = (new FormDefinitionNormalizer())->normalize($raw);

        return array_merge(['link' => $link], $normalized);
    }

    private function cacheKey(string $link): string
    {
        return 'msforms-definition:' . md5($link);
    }
}
