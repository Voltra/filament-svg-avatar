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

    public function get(Model|Authenticatable $record): string
    {
        $initials = $this->avatarService->getInitials($record);
        $svg = $this->avatarService->svg($initials);
        $svgStr = base64_encode($svg);

        return "data:image/svg+xml;base64,{$svgStr}";
    }
}
