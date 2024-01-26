<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Spatie\Color\Hex;
use Voltra\FilamentSvgAvatar\Contracts\SvgAvatarServiceContract;
use Voltra\FilamentSvgAvatar\FilamentSvgAvatarPlugin;
use Voltra\FilamentSvgAvatar\FilamentSvgAvatarServiceProvider;
use Voltra\FilamentSvgAvatar\Services\FilamentSvgAvatarService;

describe(FilamentSvgAvatarService::class, function () {
    beforeEach(function () {
        $appSoftReset = \Closure::bind(function (Application $app) {
            //            $app->forgetInstances();
            //            $app->flush();

            //            $app->__construct($app->basePath());
        }, null, Application::class);

        Mockery::close();
        Config::set('filament-svg-avatar', []);
        $appSoftReset(App::getInstance());
        App::register(FilamentSvgAvatarServiceProvider::class);
    });

    afterEach(function () {
        Mockery::close();
    });

    it('generates the right minified SVG markup', function (string $bg, string $fg, string $text, string $font) {
        $panel = new class() extends Panel
        {
        };

        $panel
            ->default()
            ->font($font);

        Filament::setCurrentPanel($panel);

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
        'lime' => ['135,226,9', '#000000', 'AB', 'Xyz'], // Contrast: 12.91
        'skyblue' => ['107,234,251', '#000000', 'CD', 'Xyz'], // Contrast: 14.78
        'darkblue' => ['46,17,100', '#ffffff', 'EF', 'Xyz'], // Contrast: 15.21
    ]);

    it('can have its background color customized through the panel plugin', function () {
        $panel = new class() extends Panel
        {
        };
        $color = Hex::fromString('#f0f0f0');
        $panel->plugin(FilamentSvgAvatarPlugin::make()->backgroundColor($color));
        Filament::setCurrentPanel($panel);

        /**
         * @var FilamentSvgAvatarService $service
         */
        $service = resolve(FilamentSvgAvatarService::class);

        expect($service->getBackgroundColor())->toBe($color);
    });

    it('can have its text color customized through the panel plugin', function () {
        $panel = new class() extends Panel
        {
        };
        $color = Hex::fromString('#3b5998');
        $panel->plugin(FilamentSvgAvatarPlugin::make()->textColor($color));
        Filament::setCurrentPanel($panel);

        /**
         * @var FilamentSvgAvatarService $service
         */
        $service = resolve(FilamentSvgAvatarService::class);

        expect($service->getTextColor())->toBe($color);
    });

    it('can be overridden to disallow panel overrides', function () {
        $panel = new class() extends Panel
        {
        };

        $pluginMock = Mockery::mock(FilamentSvgAvatarPlugin::class);

        $allowedMethods = ['backgroundColor', 'textColor', 'getId', 'register', 'boot'];

        foreach ($allowedMethods as $allowedMethod) {
            //            $pluginMock->shouldReceive($allowedMethod)->passthru();
            $pluginMock->expects($allowedMethod)->passthru()->zeroOrMoreTimes();
        }

        $forbiddenMethods = ['getTextColor', 'getBackgroundColor'];

        foreach ($forbiddenMethods as $forbiddenMethod) {
            //            $pluginMock->shouldReceive($forbiddenMethod)->never();
            $pluginMock->expects($forbiddenMethod)->never();
        }

        /** @var FilamentSvgAvatarPlugin $pluginMock */
        $pluginMock
            ->backgroundColor(Hex::fromString('#e9ebee'))
            ->textColor(Hex::fromString('#3b5998'));

        $panel->plugin($pluginMock);
        Filament::setCurrentPanel($panel);

        class SService extends FilamentSvgAvatarService
        {
            protected bool $disallowPluginOverride = true;
        }

        App::scoped(SvgAvatarServiceContract::class, SService::class);

        /**
         * @var SvgAvatarServiceContract $service
         */
        $service = resolve(SvgAvatarServiceContract::class);

        $service->getTextColor();
        $service->getBackgroundColor();
    });

    it('can be overridden to have a fixed background color', function () {
        function sservice2()
        {
            return Hex::fromString('#3c3f41');
        }

        $color = sservice2();

        class SService2 extends FilamentSvgAvatarService
        {
            public function __construct()
            {
                $this->backgroundColor = sservice2();
            }
        }

        App::scoped(SvgAvatarServiceContract::class, SService2::class);

        /**
         * @var FilamentSvgAvatarService $service
         */
        $service = resolve(SvgAvatarServiceContract::class);

        expect($service->getBackgroundColor())->toEqual($color);
    });

    it('can be overridden to have a fixed text color', function () {
        function sservice3()
        {
            return Hex::fromString('#eb53fe');
        }

        $color = sservice3();

        class SService3 extends FilamentSvgAvatarService
        {
            public function __construct()
            {
                $this->textColor = sservice3();
            }
        }

        App::scoped(SvgAvatarServiceContract::class, SService3::class);

        /**
         * @var FilamentSvgAvatarService $service
         */
        $service = resolve(SvgAvatarServiceContract::class);

        expect($service->getTextColor())->toEqual($color);
    });

    it('can be overridden to use a different size', function () {
        $panel = new class() extends Panel
        {
        };
        Filament::setCurrentPanel($panel);

        function sservice4()
        {
            return 168;
        }

        $size = sservice4();

        class SService4 extends FilamentSvgAvatarService
        {
            public function __construct()
            {
                $this->svgSize = sservice4();
            }
        }

        App::scoped(SvgAvatarServiceContract::class, SService4::class);

        /**
         * @var FilamentSvgAvatarService $service
         */
        $service = resolve(SvgAvatarServiceContract::class);

        $svg = $service->svg('TE');

        expect($svg)->toContain(sprintf('<rect x="0" y="0" width="%d" height="%d"', $size, $size))
            ->and($svg)->toContain(sprintf('<svg width="%dpx" height="%dpx"', $size, $size));
    });

    it('can be overridden to use a global background color via config, and it takes precedence over other overrides', function () {
        $color = '#3c3f41';
        Config::set('filament-svg-avatar.backgroundColor', $color);

        $expected = Hex::fromString($color);

        $panel = new class() extends Panel
        {
        };
        Filament::setCurrentPanel($panel);

        class SService5 extends FilamentSvgAvatarService
        {
            public function __construct()
            {
                $this->backgroundColor = Hex::fromString('#e9ebee');
            }
        }

        App::scoped(SvgAvatarServiceContract::class, SService5::class);

        /**
         * @var FilamentSvgAvatarService $service
         */
        $service = resolve(SvgAvatarServiceContract::class);

        expect($service->getBackgroundColor())->toEqual($expected);
    });

    it('can be overridden to use a global text color via config, and it takes precedence over other overrides', function () {
        $color = '#3c3f41';
        Config::set('filament-svg-avatar.textColor', $color);

        $expected = Hex::fromString($color);

        $panel = new class() extends Panel
        {
        };
        Filament::setCurrentPanel($panel);

        class SService6 extends FilamentSvgAvatarService
        {
            public function __construct()
            {
                $this->textColor = Hex::fromString('#e9ebee');
            }
        }

        App::scoped(SvgAvatarServiceContract::class, SService6::class);

        /**
         * @var FilamentSvgAvatarService $service
         */
        $service = resolve(SvgAvatarServiceContract::class);

        expect($service->getTextColor())->toEqual($expected);
    });

    it('can be overridden to use a global font-family via config, and it takes precedence over other overrides', function () {
        $fontFamily = 'Meredith, sans, undertale, serif';
        Config::set('filament-svg-avatar.fontFamily', $fontFamily);

        $panel = new class() extends Panel
        {
        };
        $panel->font('My font family');
        Filament::setCurrentPanel($panel);

        App::scoped(SvgAvatarServiceContract::class, FilamentSvgAvatarService::class);

        /**
         * @var FilamentSvgAvatarService $service
         */
        $service = resolve(SvgAvatarServiceContract::class);

        expect($service->getFontFamily())->toEqual($fontFamily);
    });

    it('can be overridden to use a global text DY via config, and it takes precedence over other overrides', function () {
        $textDy = '-3rem';
        Config::set('filament-svg-avatar.textDy', $textDy);

        $panel = new class() extends Panel
        {
        };
        Filament::setCurrentPanel($panel);

        class SService7 extends FilamentSvgAvatarService
        {
            public function __construct()
            {
                $this->textDy = '2em';
            }
        }

        App::scoped(SvgAvatarServiceContract::class, SService7::class);

        /**
         * @var FilamentSvgAvatarService $service
         */
        $service = resolve(SvgAvatarServiceContract::class);

        expect($service->getTextDy())->toEqual($textDy);
    });

    it('can be overridden to use a global svg size via config, and it takes precedence over other overrides', function () {
        $svgSize = 2400;
        Config::set('filament-svg-avatar.svgSize', $svgSize);

        $panel = new class() extends Panel
        {
        };
        Filament::setCurrentPanel($panel);

        class SService8 extends FilamentSvgAvatarService
        {
            public function __construct()
            {
                $this->svgSize = 120;
            }
        }

        App::scoped(SvgAvatarServiceContract::class, SService8::class);

        /**
         * @var FilamentSvgAvatarService $service
         */
        $service = resolve(SvgAvatarServiceContract::class);

        expect($service->getSize())->toEqual($svgSize);
    });

    it('can be overridden to globally disable panel overrides via config, and it takes precedence over other overrides', function () {
        Config::set('filament-svg-avatar.disallowPluginOverride', true);

        $panel = new class() extends Panel
        {
        };

        $plugin = FilamentSvgAvatarPlugin::make();

        $pluginBg = Hex::fromString('#e9ebee');
        $pluginFg = Hex::fromString('#3b5998');

        $plugin
            ->backgroundColor($pluginBg)
            ->textColor($pluginFg);

        $panel->plugin($plugin);
        Filament::setCurrentPanel($panel);

        class SService9 extends FilamentSvgAvatarService
        {
            protected bool $disallowPluginOverride = false;

            public function __construct()
            {
                $this->backgroundColor = Hex::fromString('#000');
                $this->textColor = Hex::fromString('#fff');
            }


        }

        App::scoped(SvgAvatarServiceContract::class, SService9::class);

        /**
         * @var SvgAvatarServiceContract $service
         */
        $service = resolve(SvgAvatarServiceContract::class);

        expect($service->getTextColor())->not->toBe($pluginFg);
        expect($service->getBackgroundColor())->not->toBe($pluginBg);
    });
});
