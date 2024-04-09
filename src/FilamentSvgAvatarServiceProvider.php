<?php

declare(strict_types=1);

namespace Voltra\FilamentSvgAvatar;

use Filament\Support\Assets\Asset;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Voltra\FilamentSvgAvatar\Components\Avatar;
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
            ->hasConfigFile()
            ->hasViews()
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub($this->getRepoName());
            });
    }

    public function packageRegistered(): void
    {
        parent::packageRegistered();
        $this->app->scoped(SvgAvatarServiceContract::class, FilamentSvgAvatarService::class);
    }

    public function packageBooted(): void
    {
        parent::packageBooted();
        Blade::componentNamespace(Str::beforeLast(Avatar::class, '\\'), self::$viewNamespace);

        $this->publishes([
            $this->package->basePath('/../resources/overrides/views/filament-core-avatar-override.blade.php') => resource_path('views/vendor/filament/components/avatar.blade.php'),
        ], 'filament-svg-avatar-core-overrides');
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
