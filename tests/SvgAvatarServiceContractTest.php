<?php

declare(strict_types=1);

use Illuminate\Support\Facades\App;
use Voltra\FilamentSvgAvatar\Contracts\SvgAvatarServiceContract;
use Voltra\FilamentSvgAvatar\FilamentSvgAvatarServiceProvider;
use Voltra\FilamentSvgAvatar\Services\FilamentSvgAvatarService;

describe(SvgAvatarServiceContract::class, function () {
    beforeEach(function () {
        App::forgetInstances();
        App::flush();
    });

    it('can be resolved as the overridden service instance', function () {
        App::register(FilamentSvgAvatarServiceProvider::class);

        class Service extends FilamentSvgAvatarService
        {
        }

        class Provider extends \Illuminate\Support\ServiceProvider
        {
            public function register(): void
            {
                $this->app->scoped(SvgAvatarServiceContract::class, Service::class);
            }
        }

        App::register(Provider::class);

        expect(resolve(SvgAvatarServiceContract::class))->toBeInstanceOf(Service::class);
    });
});
