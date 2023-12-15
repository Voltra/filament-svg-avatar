#  Change the default avatar url provider with one for inline SVGs

[![Latest Version on Packagist](https://img.shields.io/packagist/v/voltra/filament-svg-avatar.svg?style=flat-square)](https://packagist.org/packages/voltra/filament-svg-avatar)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/voltra/filament-svg-avatar/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/voltra/filament-svg-avatar/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/voltra/filament-svg-avatar/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/voltra/filament-svg-avatar/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/voltra/filament-svg-avatar.svg?style=flat-square)](https://packagist.org/packages/voltra/filament-svg-avatar)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require voltra/filament-svg-avatar
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-svg-avatar-migrations"
php artisan migrate
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
];
```

## Usage

```php
$filamentSvgAvatar = new Voltra\FilamentSvgAvatar();
echo $filamentSvgAvatar->echoPhrase('Hello, Voltra!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Voltra](https://github.com/Voltra)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
