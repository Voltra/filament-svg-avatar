<?php

declare(strict_types=1);

use Illuminate\Support\Facades\App;
use Voltra\FilamentSvgAvatar\FilamentSvgAvatarServiceProvider;
use Voltra\FilamentSvgAvatar\Services\FilamentSvgAvatarService;

describe(FilamentSvgAvatarService::class, function () {
    beforeEach(function () {
        App::forgetInstances();
        App::flush();
        App::register(FilamentSvgAvatarServiceProvider::class);
    });

    it('generates the right minified SVG markup', function (string $bg, string $fg, string $text, string $font) {
        $panel = new class() extends \Filament\Panel
        {
        };

        $panel
            ->default()
            ->font($font);

        \Filament\Facades\Filament::setCurrentPanel($panel);

        \Filament\Support\Facades\FilamentColor::shouldReceive('getColors')
            ->twice() // one for the bg, one for the fg
            ->andReturn([
                'primary' => [
                    500 => $bg,
                ],
            ]);

        /**
         * @var FilamentSvgAvatarService $service
         */
        $service = resolve(FilamentSvgAvatarService::class);
        $svg = $service->svg($text);

        $refl = new ReflectionProperty($service, 'svgSize');
        $svgSize = $refl->getValue($service);
        $dy = $service->getTextDy();

        $expected = strtr(
            '<svg width="{svgSize}px" height="{svgSize}px" viewBox="0 0 {svgSize} {svgSize}" xmlns="http://www.w3.org/2000/svg"><rect x="0" y="0" width="{svgSize}" height="{svgSize}" rx="0" style="fill:rgb({bgRgb});"/><text x="50%" y="50%" dy="{dy}" fill="{fill}" text-anchor="middle" dominant-baseline="middle" style="font-family: {font}; font-size: {fontSizePx}px; line-height: 1;">{text}</text></svg>',
            [
                '{svgSize}' => $svgSize,
                '{bgRgb}' => $bg,
                '{fill}' => $fg,
                '{font}' => $font,
                '{fontSizePx}' => floor($svgSize / 2),
                '{dy}' => $dy,
                '{text}' => $text,
            ]
        );
        expect($svg)->toEqual($expected);
    })->with([
        'white' => ['255,255,255', '#000000', 'LG', 'Inter'], // Contrast: 21
        'black' => ['0,0,0', '#ffffff', 'SF', 'Poppins'], // Contrast: 21
        'turquoise' => ['84,216,200', '#000000', 'VN', 'StuffFont'], // Contrast: 12.03
        'lavender' => ['119,104,174', '#ffffff', 'PV', 'Xyz'], // Contrast: 4.79 (vs 4.31)
        'lime' => ['135,226,9', '#000000', 'PV', 'Xyz'], // Contrast: 12.91
        'skyblue' => ['107,234,251', '#000000', 'PV', 'Xyz'], // Contrast: 14.78
        'darkblue' => ['46,17,100', '#ffffff', 'PV', 'Xyz'], // Contrast: 15.21
    ]);
});
