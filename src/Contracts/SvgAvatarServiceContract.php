<?php

declare(strict_types=1);

namespace Voltra\FilamentSvgAvatar\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Color\Color;

interface SvgAvatarServiceContract
{
    /**
     * Get the initials that are to be displayed on the avatar
     *
     * @param  Model|Authenticatable  $user  - The user to get the initials for
     */
    public function getInitials(Model|Authenticatable $user): string;

    /**
     * Get the SVG markup that constitues the avatar
     *
     * @param  string  $text  - The text that is to be displayed on the avatar
     */
    public function svg(string $text): string;

    /**
     * Get the background color for the avatar
     */
    public function getBackgroundColor(): Color;

    /**
     * Get the text color for the avatar
     *
     * @note Used as the value for the fill SVG attribute
     */
    public function getTextColor(): Color;

    /**
     * Get the font-family for the avatar
     *
     * @note Used as the value of the font-family CSS property
     */
    public function getFontFamily(): string;

    /**
     * Get the Y-axis offset for the SVG text
     *
     * @note Used as the dy SVG attribute
     */
    public function getTextDy(): string;
}
