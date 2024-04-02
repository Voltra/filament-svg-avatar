<?php

declare(strict_types=1);

use Filament\Panel;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Voltra\FilamentSvgAvatar\Filament\AvatarProviders\RawSvgAvatarProvider;
use Voltra\FilamentSvgAvatar\Filament\AvatarProviders\SvgAvatarsProviders;
use Voltra\FilamentSvgAvatar\FilamentSvgAvatarPlugin;
use Voltra\FilamentSvgAvatar\FilamentSvgAvatarServiceProvider;

describe(FilamentSvgAvatarPlugin::class, function () {
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

    it('registers SvgAvatarsProviders as the default provider if none is set', function () {
        $panel = new class() extends Panel
        {
        };

        $panel
            ->default()
            ->plugin(FilamentSvgAvatarPlugin::make());

        expect($panel->getDefaultAvatarProvider())->toBe(SvgAvatarsProviders::class);
    });

    it('doesn\'t register SvgAvatarsProviders as the default provider if we provide another one from the package', function () {
        $panel = new class() extends Panel
        {
        };

        $panel
            ->default()
            ->plugin(FilamentSvgAvatarPlugin::make())
            ->defaultAvatarProvider(RawSvgAvatarProvider::class);

        expect($panel->getDefaultAvatarProvider())->toBe(RawSvgAvatarProvider::class);

        unset($panel);

        $panel = new class() extends Panel
        {
        };

        $panel
            ->default()
            ->defaultAvatarProvider(RawSvgAvatarProvider::class)
            ->plugin(FilamentSvgAvatarPlugin::make());

        expect($panel->getDefaultAvatarProvider())->toBe(RawSvgAvatarProvider::class);
    });

    it('registers SvgAvatarsProviders as the default provider if we provide another one that is not from the package (before registering the plugin)', function () {
        $provider = 'My\\Hypothetical\\AvatarProvider';

        $panel = new class() extends Panel
        {
        };

        $panel
            ->default()
            ->defaultAvatarProvider($provider)
            ->plugin(FilamentSvgAvatarPlugin::make());

        expect($panel->getDefaultAvatarProvider())->toBe(SvgAvatarsProviders::class);
    });

    it('doesn\'t change the default provider if we provide another one that is not from the package (after registering the plugin)', function () {
        $provider = 'My\\Hypothetical\\AvatarProvider';

        $panel = new class() extends Panel
        {
        };

        $panel
            ->default()
            ->plugin(FilamentSvgAvatarPlugin::make())
            ->defaultAvatarProvider($provider);

        expect($panel->getDefaultAvatarProvider())->toBe($provider);
    });
});
