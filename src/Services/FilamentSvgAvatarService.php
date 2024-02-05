<?php

declare(strict_types=1);

namespace Voltra\FilamentSvgAvatar\Services;

use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Spatie\Color\Color;
use Spatie\Color\Contrast;
use Spatie\Color\Hex;
use Spatie\Color\Rgb;
use Voltra\FilamentSvgAvatar\Components\Avatar;
use Voltra\FilamentSvgAvatar\Contracts\SvgAvatarServiceContract;
use Voltra\FilamentSvgAvatar\FilamentSvgAvatarPlugin;

class FilamentSvgAvatarService implements SvgAvatarServiceContract
{
    /**
     * The width/height (in pixels) of the avatar SVG to generate
     */
    protected int $svgSize = 500;

    /**
     * Override for the background color
     */
    protected ?Color $backgroundColor = null;

    /**
     * Override for the text color
     */
    protected ?Color $textColor = null;

    /**
     * Whether to disallow the plugin from overriding colors
     */
    protected bool $disallowPluginOverride = false;

    /**
     * Offset of the text in the SVG
     */
    protected string $textDy = '.1em';

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
        $bgColor = $this->getBackgroundColor();
        $fontColor = $this->getTextColor();
        $font = $this->getFontFamily();

        $size = $this->getSize();
        $textSize = floor($size / 2);
        $dy = $this->getTextDy();

        $avatar = new Avatar(
            service: $this,
            initials: $text,
            dontInheritFontFamily: true, // <img/> don't properly handle inheriting fonts, and filament's avatar component uses one
            backgroundColor: $bgColor,
            textColor: $fontColor,
            size: $size,
            fontFamily: $font,
            dy: $dy,
            fontSize: (int) $textSize,
        );

        $svg = Blade::renderComponent($avatar);

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
        $configValue = Config::get('filament-svg-avatar.backgroundColor', null);

        if (! empty($configValue)) {
            return Hex::fromString($configValue);
        }

        if ($this->backgroundColor) {
            return $this->backgroundColor;
        }

        $bg = $this->disallowsPluginOverrides() ? null : $this->getPlugin()?->getBackgroundColor();

        return $bg
            ?? Rgb::fromString('rgb('.FilamentColor::getColors()['primary'][500].')');
    }

    /**
     * {@inheritDoc}
     */
    public function getTextColor(): Color
    {
        $configValue = Config::get('filament-svg-avatar.textColor', null);

        if (! empty($configValue)) {
            return Hex::fromString($configValue);
        }

        if ($this->textColor) {
            return $this->textColor;
        }

        if (! $this->disallowsPluginOverrides()) {
            $textCol = $this->getPlugin()?->getTextColor();

            if (! empty($textCol)) {
                return $textCol;
            }
        }

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
        $configValue = Config::get('filament-svg-avatar.fontFamily', null);

        if (! empty($configValue)) {
            return $configValue;
        }

        return Filament::getFontFamily();
    }

    /**
     * {@inheritDoc}
     */
    public function getTextDy(): string
    {
        return Config::get('filament-svg-avatar.textDy', $this->textDy) ?? $this->textDy;
    }

    //    /**
    //     * {@inheritDoc}
    //     */
    public function getSize(): int
    {
        return Config::get('filament-svg-avatar.svgSize', $this->svgSize) ?? $this->svgSize;
    }

    /**
     * Whether plugin overrides are disallowed
     */
    public function disallowsPluginOverrides(): bool
    {
        $fromConfig = Config::get('filament-svg-avatar.disallowPluginOverride', $this->disallowPluginOverride) ?? $this->disallowPluginOverride;

        return $fromConfig || $this->disallowPluginOverride;
    }

    protected function getPlugin(): ?FilamentSvgAvatarPlugin
    {
        $id = FilamentSvgAvatarPlugin::ID;

        if (! Filament::hasPlugin($id)) {
            return null;
        }

        $plugin = Filament::getPlugin($id);

        return $plugin instanceof FilamentSvgAvatarPlugin ? $plugin : null;
    }
}
