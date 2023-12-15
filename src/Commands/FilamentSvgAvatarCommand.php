<?php

namespace Voltra\FilamentSvgAvatar\Commands;

use Illuminate\Console\Command;

class FilamentSvgAvatarCommand extends Command
{
    public $signature = 'filament-svg-avatar';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
