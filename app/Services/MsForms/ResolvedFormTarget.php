<?php

namespace App\Services\MsForms;

final class ResolvedFormTarget
{
    public function __construct(
        public readonly string $apiBaseUrl,
        public readonly string $formId,
    ) {
    }
}
