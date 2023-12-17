<?php

declare(strict_types=1);

namespace Voltra\FilamentSvgAvatar;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Voltra\FilamentSvgAvatar\Filament\AvatarProviders\SvgAvatarsProviders;

/**
 * Use this package as a panel plugin that automatically registers the default avatar provider
 */
class FilamentSvgAvatarPlugin implements Plugin
{
    const ID = 'filament-svg-avatar';

    final public function getId(): string
    {
        return self::ID;
    }

    public function register(Panel $panel): void
    {
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
