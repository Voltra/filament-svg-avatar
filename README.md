![Filament Svg Avatar: Change the default avatar url provider with one for inline SVGs](https://raw.githubusercontent.com/Voltra/filament-svg-avatar/main/art/banner.jpeg)

#  voltra/filament-svg-avatar

[![Latest Version on Packagist](https://img.shields.io/packagist/v/voltra/filament-svg-avatar.svg?style=flat-square)](https://packagist.org/packages/voltra/filament-svg-avatar)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/voltra/filament-svg-avatar/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/voltra/filament-svg-avatar/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/voltra/filament-svg-avatar/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/voltra/filament-svg-avatar/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/voltra/filament-svg-avatar.svg?style=flat-square)](https://packagist.org/packages/voltra/filament-svg-avatar)

Change the default avatar url provider with one for inline SVGs.

No more external HTTP requests just for default avatars.

## Installation

You can install the package via composer:

```bash
composer require voltra/filament-svg-avatar
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-svg-avatar-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-svg-avatar-views"
```

This is the contents of the published config file:

```php
return [
    /**
     * Global config of the SVG size
     *
     * @type ?int
     *
     * @default 500
     */
    'svgSize' => null,

    /**
     * Global config for the avatar's background color (as a hex code)
     *
     * @type ?string
     */
    'backgroundColor' => null,

    /**
     * Global config for the avatar's text color (as a hex code)
     *
     * @type ?string
     */
    'textColor' => null,

    /**
     * Global config for whether to disallow the plugin from overriding colors
     *
     * @type bool
     *
     * @default false
     */
    'disallowPluginOverride' => false,

    /**
     * Global config for the avatar's font-family
     *
     * @type ?string
     *
     * @default \Filament\Facades\Filament::getFontFamily()
     */
    'fontFamily' => null,

    /**
     * Global config of the SVG size
     *
     * @type ?string
     *
     * @default '.1em'
     */
    'textDy' => null,
];
```

## Usage

### As the avatar provider

```php
class MyPanelProvider extends \Filament\PanelProvider {
    public function panel(\Filament\Panel $panel) {
        return $panel
            // [...]
            ->defaultAvatarProvider(\Voltra\FilamentSvgAvatar\Filament\AvatarProviders\SvgAvatarsProviders::class)
            // [...]
    }
}
```

### As a plugin

It automatically registers the avatar provider.

```php
class MyPanelProvider extends \Filament\PanelProvider {
    public function panel(\Filament\Panel $panel) {
        return $panel
            // [...]
            ->plugins([
                // [...]
                \Voltra\FilamentSvgAvatar\FilamentSvgAvatarPlugin::make()
                    ->backgroundColor(\Spatie\Color\Hex::fromString('#3b5998'))
                    ->textColor(\Spatie\Color\Hex::fromString('#e9ebee')),
                // [...]
]           )
            // [...]
    }
}
```

### Replace filament's default avatar component

First either publish filament's support package's views, or just create the `resources/views/vendor/filament/components/avatar.blade.php` file:

```php
@props([
    'circular' => true,
    'size' => 'md',
])

<x-filament-svg-avatar::avatar-override
    :attributes="$attributes"
    :circular="$circular"
    :size="$size"
></x-filament-svg-avatar::avatar-override>
```

This will use the `<x-filament-svg-avatar::avatar/>` component, configure it based on what `<x-filament::avatar/>` expects, and output an `<svg>` instead of an `<img/>` (which means better custom font support!).

### Extend or override

NB: Config values take precedence over overrides

Create a class that implements the `\Voltra\FilamentSvgAvatar\Contracts\SvgAvatarServiceContract` interface.

You can even extend from `\Voltra\FilamentSvgAvatar\Services\FilamentSvgAvatarService`.

Then, in a service provider, bind your implementation to the interface:
```php
class MyServiceProvider extends \Illuminate\Support\ServiceProvider {
    // [...]
        
    public function register() {
        // [...]
        $this->app->scoped(\Voltra\FilamentSvgAvatar\Contracts\SvgAvatarServiceContract::class, MySvgAvatarServiceImpl::class);
        // [...]
    }

    // [...]
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](https://github.com/Voltra/filament-svg-avatar/blob/main/CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/Voltra/filament-svg-avatar/blob/main/.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](https://github.com/Voltra/filament-svg-avatar/security/policy) on how to report security vulnerabilities.

## Credits

- [Voltra](https://github.com/Voltra)
- [All Contributors](https://github.com/Voltra/filament-svg-avatar/contributors)

## License

The MIT License (MIT). Please see [License File](https://github.com/Voltra/filament-svg-avatar/blob/main/LICENSE.md) for more information.
