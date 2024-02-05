<?php

declare(strict_types=1);

namespace Voltra\FilamentSvgAvatar\Services;

class Utils
{
    public static function initials(string $str)
    {
        return str($str)
            ->trim()
            ->explode(' ') // Split by "word"
            ->lazy()
            ->map(fn (string $segment): string => filled($segment) ? mb_substr($segment, 0, 1) : '') // Only keep the first letter
            ->map(fn (string $initial) => mb_strtoupper($initial)) // Turn every character to uppercase
            ->take(2)
            ->join(''); // Glue it all together
    }
}
