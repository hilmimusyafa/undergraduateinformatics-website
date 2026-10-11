<?php

namespace App\Services\Security;

use HTMLPurifier;
use HTMLPurifier_Config;

class HtmlSanitizer
{
    private static ?HTMLPurifier $purifier = null;

    public static function clean(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        if (trim($html) === '') {
            return '';
        }

        if (self::$purifier === null) {
            $config = HTMLPurifier_Config::createDefault();
            $config->set(
                'HTML.Allowed',
                'p,b,strong,i,em,u,s,span[style],ul[type],ol[type],li,br,div,a[href|target|title|rel],img[src|alt|title|width|height],h1,h2,h3,h4,h5,h6,table,thead,tbody,tr,th,td,blockquote,pre,code,hr'
            );
            $config->set('HTML.TargetBlank', true);
            $config->set('Attr.AllowedRel', ['noopener', 'noreferrer']);
            $config->set('AutoFormat.AutoParagraph', false);

            self::$purifier = new HTMLPurifier($config);
        }

        return self::$purifier->purify($html);
    }
}
