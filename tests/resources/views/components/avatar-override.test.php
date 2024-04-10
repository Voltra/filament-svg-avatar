<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Lang;
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

    it('doesn\'t forward "src" or "alt"', function () {
        $render = Blade::render('<x-filament-svg-avatar::avatar-override src="https://my.url.com/img.jpg"/>');
        expect($render)->not->toContain('src="https://my.url.com/img.jpg"');

        $render = Blade::render('<x-filament-svg-avatar::avatar-override alt="Some alt text"/>');
        expect($render)->not->toContain('alt="Some alt text"');
    });

    it('does\'t use the "alt" attribute for the initials if it\'s filament\'s placeholder', function () {
        $render = Blade::render('<x-filament-svg-avatar::avatar-override src="OK" alt="filament-panels::layout.avatar.alt"/>');
        expect($render)->toContain('OK');
        expect($render)->not->toContain('FP');
        expect($render)->not->toContain('FL');
    });

    it('does\'t use the "alt" attribute for the initials if it\'s filament\'s placeholder but translated', function () {
        Lang::addLines([
            'avatar.alt' => 'Some Alt',
        ], App::getLocale(), namespace: 'filament-panels::layout');

        $alt = __('filament-panels::layout.avatar.alt');
        $render = Blade::render("<x-filament-svg-avatar::avatar-override src=\"OK\" alt=\"{$alt}\"/>");
        expect($render)->toContain('OK');
        expect($render)->not->toContain('SA');

        Lang::addLines([
            'avatar.alt' => null,
        ], App::getLocale(), namespace: 'filament-panels::layout');
    });
});
