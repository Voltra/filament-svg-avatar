<?php

declare(strict_types=1);

namespace Voltra\FilamentSvgAvatar;

use Filament\Support\Assets\Asset;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Voltra\FilamentSvgAvatar\Contracts\SvgAvatarServiceContract;
use Voltra\FilamentSvgAvatar\Services\FilamentSvgAvatarService;

class FilamentSvgAvatarServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-svg-avatar';

    public static string $viewNamespace = 'filament-svg-avatar';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub($this->getRepoName());
            });
    }

    public function packageRegistered(): void
    {
        $this->app->scoped(SvgAvatarServiceContract::class, FilamentSvgAvatarService::class);
    }

    public function packageBooted(): void
    {
    }

    protected function getRepoName(): string
    {
        return 'Voltra/filament-svg-avatar';
    }

    protected function getAssetPackageName(): ?string
    {
        return 'voltra/filament-svg-avatar';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [];
    }
}
