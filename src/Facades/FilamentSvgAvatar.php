<?php

namespace Voltra\FilamentSvgAvatar\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Voltra\FilamentSvgAvatar\FilamentSvgAvatar
 */
class FilamentSvgAvatar extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Voltra\FilamentSvgAvatar\FilamentSvgAvatar::class;
    }
}
