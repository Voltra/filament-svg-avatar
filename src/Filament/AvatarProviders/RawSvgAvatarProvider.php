<?php

declare(strict_types=1);

namespace Voltra\FilamentSvgAvatar\Filament\AvatarProviders;

use Filament\AvatarProviders\Contracts\AvatarProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Voltra\FilamentSvgAvatar\Contracts\SvgAvatarServiceContract;

class RawSvgAvatarProvider implements AvatarProvider
{
    public function __construct(protected SvgAvatarServiceContract $avatarService) {}

    public function get(Model|Authenticatable $record): string
    {
        return $this->avatarService->getInitials($record);
    }
}
