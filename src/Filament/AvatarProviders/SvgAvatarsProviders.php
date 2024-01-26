<?php

declare(strict_types=1);

namespace Voltra\FilamentSvgAvatar\Filament\AvatarProviders;

use Filament\AvatarProviders\Contracts\AvatarProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Voltra\FilamentSvgAvatar\Contracts\SvgAvatarServiceContract;

class SvgAvatarsProviders implements AvatarProvider
{
    public function __construct(protected SvgAvatarServiceContract $avatarService)
    {
    }

    /**
     * Get the SVG of the avatar for the given record
     */
    public function getSvg(Model|Authenticatable $record): string
    {
        $initials = $this->avatarService->getInitials($record);

        return $this->avatarService->svg($initials);
    }

    /**
     * {@inheritDoc}
     */
    public function get(Model|Authenticatable $record): string
    {
        $svg = $this->getSvg($record);
        $svgStr = base64_encode($svg);

        return "data:image/svg+xml;base64,{$svgStr}";
    }
}
