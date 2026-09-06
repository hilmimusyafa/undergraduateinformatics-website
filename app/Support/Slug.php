<?php

namespace App\Support;

final class Slug
{
    public static function makeUnique(string $slug, callable $exists): string
    {
        $base = $slug;
        $suffix = 2;

        while ($exists($slug)) {
            $slug = $base . '-' . $suffix++;
        }

        return $slug;
    }
}