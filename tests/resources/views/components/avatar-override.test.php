<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Voltra\FilamentSvgAvatar\FilamentSvgAvatarServiceProvider;

describe('<x-filament-svg-avatar::avatar-override/>', function () {
    beforeEach(function () {
        App::register(FilamentSvgAvatarServiceProvider::class);

        $panel = new class() extends Panel
        {
        };
        Filament::setCurrentPanel($panel);
    });

    it('properly forwards the attributes', function () {
        $render = Blade::render('<x-filament-svg-avatar::avatar-override preserveAspectRatio="none none"/>');
        expect($render)->toContain('class="fi-avatar ');
        expect($render)->toContain('preserveAspectRatio="none none"');
    });
});
