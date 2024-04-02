<?php

declare(strict_types=1);

namespace Voltra\FilamentSvgAvatar;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Spatie\Color\Color;
use Voltra\FilamentSvgAvatar\Filament\AvatarProviders\RawSvgAvatarProvider;
use Voltra\FilamentSvgAvatar\Filament\AvatarProviders\SvgAvatarsProviders;

/**
 * Use this package as a panel plugin that automatically registers the default avatar provider
 */
class FilamentSvgAvatarPlugin implements Plugin
{
    const ID = 'filament-svg-avatar';

    const AVAILABLE_PROVIDERS = [
        SvgAvatarsProviders::class,
        RawSvgAvatarProvider::class,
    ];

    /**
     * Override for the background color
     */
    protected ?Color $backgroundColor = null;

    /**
     * Override for the text color
     */
    protected ?Color $textColor = null;

    /**
     * Set the override background color
     *
     * @return $this
     */
    public function backgroundColor(?Color $backgroundColor): self
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    /**
     * Set the override text color
     *
     * @return $this
     */
    public function textColor(?Color $textColor): self
    {
        $this->textColor = $textColor;

        return $this;
    }

    /**
     * Get the override background color
     */
    public function getBackgroundColor(): ?Color
    {
        return $this->backgroundColor;
    }

    /**
     * Get the override text color
     */
    public function getTextColor(): ?Color
    {
        return $this->textColor;
    }

    ////

    final public function getId(): string
    {
        return self::ID;
    }

    public function register(Panel $panel): void
    {
        $class = $panel->getDefaultAvatarProvider();

        // If we already have a provider registered, and it's one provided by this package
        // then we don't want to add our optional default here. This makes it so that library
        // users can register the plugin AND register one of our providers manually (e.g. RawSvgAvatarProvider)
        if (! empty($class) && in_array($class, static::AVAILABLE_PROVIDERS)) {
            return;
        }

        $panel->defaultAvatarProvider(SvgAvatarsProviders::class);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(self::ID);

        return $plugin;
    }
}
