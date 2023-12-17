<?php

declare(strict_types=1);

namespace Voltra\FilamentSvgAvatar\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string getInitials(\Illuminate\Database\Eloquent\Model|\Illuminate\Contracts\Auth\Authenticatable $user)
 * @method static string svg(string $text)
 * @method static \Spatie\Color\Color getBackgroundColor()
 * @method static \Spatie\Color\Color getTextColor()
 * @method static string getFontFamily()
 * @method static string getTextDy()
 *
 * @see \Voltra\FilamentSvgAvatar\Contracts\SvgAvatarServiceContract
 */
class FilamentSvgAvatar extends Facade
{
    /**
     * {@inheritDoc}
     *
     * @returns class-string<\Voltra\FilamentSvgAvatar\Contracts\SvgAvatarServiceContract>
     */
    protected static function getFacadeAccessor(): string
    {
        return \Voltra\FilamentSvgAvatar\Contracts\SvgAvatarServiceContract::class;
    }
}
