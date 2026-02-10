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

    /**
     * @precondition is_array(sscanf($color, 'oklch(%f %f %f)'))
     *
     * @see https://github.com/filamentphp/support/blob/5.x/src/Colors/Color.php#L381
     */
    public static function oklchToRgb(string $color): string
    {
        // Parse the OKLCH values
        [$lightness, $chroma, $hue] = sscanf($color, 'oklch(%f %f %f)');

        // Convert hue to radians
        $hue = deg2rad($hue);

        // Convert chroma to linear RGB
        $colorOpponentA = $chroma * cos($hue);
        $colorOpponentB = $chroma * sin($hue);

        $long = $lightness + 0.3963377774 * $colorOpponentA + 0.2158037573 * $colorOpponentB;
        $medium = $lightness - 0.1055613458 * $colorOpponentA - 0.0638541728 * $colorOpponentB;
        $short = $lightness - 0.0894841775 * $colorOpponentA - 1.2914855480 * $colorOpponentB;

        $long = pow($long, 3);
        $medium = pow($medium, 3);
        $short = pow($short, 3);

        $red = 4.0767416621 * $long - 3.3077115913 * $medium + 0.2309699292 * $short;
        $green = -1.2684380046 * $long + 2.6097574011 * $medium - 0.3413193965 * $short;
        $blue = -0.0041960863 * $long - 0.7034186147 * $medium + 1.7076147010 * $short;

        // Convert linear RGB to sRGB
        $red = $red <= 0.0031308 ? 12.92 * $red : 1.055 * pow($red, 1 / 2.4) - 0.055;
        $green = $green <= 0.0031308 ? 12.92 * $green : 1.055 * pow($green, 1 / 2.4) - 0.055;
        $blue = $blue <= 0.0031308 ? 12.92 * $blue : 1.055 * pow($blue, 1 / 2.4) - 0.055;

        // Convert to range between 0 and 255
        $red = round($red * 255);
        $green = round($green * 255);
        $blue = round($blue * 255);

        // Ensure values are in range between 0 and 255
        $red = max(0, min(255, $red));
        $green = max(0, min(255, $green));
        $blue = max(0, min(255, $blue));

        return "rgb({$red}, {$green}, {$blue})";
    }
}
