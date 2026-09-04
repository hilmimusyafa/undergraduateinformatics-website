<?php

namespace App\Services\MsForms;

use HTMLPurifier;
use HTMLPurifier_Config;

final class RichTextSanitizer
{
    private HTMLPurifier $purifier;

    public function __construct()
    {
        $config = HTMLPurifier_Config::createDefault();

        $config->set('HTML.Allowed', 'b,strong,i,em,u,span,ul,ol,li,br,div,p,a[href]');
        $config->set('HTML.TargetBlank', true);
        $config->set('Attr.AllowedRel', ['noopener', 'noreferrer']);
        $config->set('AutoFormat.AutoParagraph', false);

        $this->purifier = new HTMLPurifier($config);
    }

    public function sanitize(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        $clean = trim($this->purifier->purify($html));

        if ($clean === '') {
            return null;
        }

        return $this->unwrapSingleBlockWrapper($clean);
    }

    public function sanitizeRich(?string $html): ?string
    {
        $sanitized = $this->sanitize($html);

        if ($sanitized === null || $this->isRich($sanitized) === false) {
            return null;
        }

        return $sanitized;
    }

    public function isRich(string $html): bool
    {
        return preg_match('#<(b|strong|i|em|u|span|ul|ol|li|br|div|p|a)(\s|>)#i', $html) === 1;
    }

    private function unwrapSingleBlockWrapper(string $html): string
    {
        if (preg_match('#^<(p|div)[^>]*>((?:(?!</\1\b).)*)</\1>$#s', $html, $matches) === 1) {
            return trim($matches[2]);
        }

        return $html;
    }
}
