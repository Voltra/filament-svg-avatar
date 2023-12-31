<?php

declare(strict_types=1);

namespace Voltra\FilamentSvgAvatar\Services;

use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Color\Color;
use Spatie\Color\Contrast;
use Spatie\Color\Hex;
use Spatie\Color\Rgb;
use Voltra\FilamentSvgAvatar\Contracts\SvgAvatarServiceContract;

class FilamentSvgAvatarService implements SvgAvatarServiceContract
{
    /**
     * The width/height (in pixels) of the avatar SVG to generate
     */
    protected int $svgSize = 500;

    //

    /**
     * {@inheritDoc}
     */
    public function getInitials(Model|Authenticatable $user): string
    {
        return str(Filament::getNameForDefaultAvatar($user))
            ->trim()
            ->explode(' ') // Split by "word"
            ->lazy()
            ->map(fn (string $segment): string => filled($segment) ? mb_substr($segment, 0, 1) : '') // Only keep the first letter
            ->map(fn (string $initial) => mb_strtoupper($initial)) // Turn every character to uppercase
            ->take(2)
            ->join(''); // Glue it all together
    }

    /**
     * {@inheritDoc}
     */
    public function svg(string $text): string
    {
        $bgColor = (string) $this->getBackgroundColor();
        $fontColor = (string) $this->getTextColor();
        $font = $this->getFontFamily();

        $size = $this->svgSize;
        $textSize = floor($size / 2);
        $dy = $this->getTextDy();

        $svg = <<<SVG
            <svg width="{$size}px" height="{$size}px" viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg">
                <rect x="0" y="0" width="{$size}" height="{$size}" rx="0" style="fill:{$bgColor};"/>
                <text x="50%" y="50%" dy="{$dy}" fill="{$fontColor}" text-anchor="middle" dominant-baseline="middle" style="font-family: {$font}; font-size: {$textSize}px; line-height: 1;">
                    {$text}
                </text>
            </svg>
        SVG;

        return preg_replace([
            '/>\s+</m',
            '/>\s+/m',
            '/\s+</m',
        ], [
            '><',
            '>',
            '<',
        ], $svg);
    }

    /**
     * {@inheritDoc}
     */
    public function getBackgroundColor(): Color
    {
        return Rgb::fromString('rgb('.FilamentColor::getColors()['primary'][500].')');
    }

    /**
     * {@inheritDoc}
     */
    public function getTextColor(): Color
    {
        $bg = $this->getBackgroundColor();
        $white = Hex::fromString('#fff');

        $ratioToWhite = Contrast::ratio($bg, $white);

        if ($ratioToWhite >= 7) {
            return $white;
        }

        $black = Hex::fromString('#000');
        $ratioToBlack = Contrast::ratio($bg, $black);

        if ($ratioToBlack >= 7) {
            return $black;
        }

        return $ratioToWhite < $ratioToBlack ? $black : $white;
    }

    /**
     * {@inheritDoc}
     */
    public function getFontFamily(): string
    {
        return Filament::getFontFamily();
    }

    /**
     * {@inheritDoc}
     */
    public function getTextDy(): string
    {
        return '.1em';
    }
}
