<?php

declare(strict_types=1);

use Illuminate\Support\Facades\App;
use Voltra\FilamentSvgAvatar\Contracts\SvgAvatarServiceContract;
use Voltra\FilamentSvgAvatar\FilamentSvgAvatarServiceProvider;
use Voltra\FilamentSvgAvatar\Services\FilamentSvgAvatarService;

describe(SvgAvatarServiceContract::class, function () {
    beforeEach(function () {
        /*App::forgetInstances();
        App::flush();*/
        App::instance(FilamentSvgAvatarServiceProvider::class, null);
    });

    it('can be resolved as the overridden service instance', function () {
        App::register(FilamentSvgAvatarServiceProvider::class);

        class CService extends FilamentSvgAvatarService {}

        App::scoped(SvgAvatarServiceContract::class, CService::class);

        expect(resolve(SvgAvatarServiceContract::class))->toBeInstanceOf(CService::class);
    });
});
