# Laravel-sanitized

[![Latest Version on Packagist](https://img.shields.io/packagist/v/opiy-org/laravel-sanitized.svg?style=flat-square)](https://packagist.org/packages/opiy-org/laravel-sanitized)
[![Total Downloads](https://img.shields.io/packagist/dt/opiy-org/laravel-sanitized.svg?style=flat-square)](https://packagist.org/packages/opiy-org/laravel-sanitized)
![GitHub Actions](https://github.com/opiy-org/laravel-sanitized/actions/workflows/main.yml/badge.svg)

Clean html in eloquent model fields on saving

## Installation

You can install the package via composer:

```bash
composer require opiy-org/laravel-sanitized
```

## Usage

Just add trait to your models:
```php
use LaravelSanitized;
```

And also add the list of model's fields you want to be sanitized:  
```php
  protected array $fieldsToSanitize = [
    'body',
    'description',
    ...
  ];
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

### Security

If you discover any security related issues, please email opiy@opiy.org instead of using the issue tracker.

## Credits

-   [opiy](https://github.com/opiy-org)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
