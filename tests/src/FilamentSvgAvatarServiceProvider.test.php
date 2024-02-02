<?php

declare(strict_types=1);

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\App;
use Voltra\FilamentSvgAvatar\Contracts\SvgAvatarServiceContract;
use Voltra\FilamentSvgAvatar\FilamentSvgAvatarServiceProvider;
use Voltra\FilamentSvgAvatar\Services\FilamentSvgAvatarService;

describe(FilamentSvgAvatarServiceProvider::class, function () {
    beforeEach(function () {
        /*App::forgetInstances();
        App::flush();*/
        App::instance(FilamentSvgAvatarServiceProvider::class, null);
    });

    it('can be registered', function () {
        expect(App::getProvider(FilamentSvgAvatarServiceProvider::class))->toBeNull();

        $provider = App::register(FilamentSvgAvatarServiceProvider::class);

        expect($provider)->toBeInstanceOf(FilamentSvgAvatarServiceProvider::class);
    });

    it('registers a binding for the SVG avatar service', function () {
        try {
            expect(resolve(SvgAvatarServiceContract::class))->toBeNull();
        } catch (Throwable $e) {
            expect($e)->toBeInstanceOf(BindingResolutionException::class);
        }

        App::register(FilamentSvgAvatarServiceProvider::class);

        $instance = resolve(SvgAvatarServiceContract::class);
        expect($instance)->not->toBeNull();
    });

    it('scopes the SVG avatar service to the default implementation', function () {
        App::register(FilamentSvgAvatarServiceProvider::class);

        $instance = resolve(SvgAvatarServiceContract::class);
        expect($instance)->toBeInstanceOf(FilamentSvgAvatarService::class);

        $instance2 = resolve(SvgAvatarServiceContract::class);
        expect($instance2)->toBe($instance);
    });
});
