# Make laravel models factories migrations rapidly

[![Latest Version on Packagist](https://img.shields.io/packagist/v/iasonargyrakis/lara-xtnder.svg?style=flat-square)](https://packagist.org/packages/iasonargyrakis/lara-xtnder)
[![Total Downloads](https://img.shields.io/packagist/dt/iasonargyrakis/lara-xtnder.svg?style=flat-square)](https://packagist.org/packages/iasonargyrakis/lara-xtnder)

## Installation

You can install the package via composer:

```bash
composer require iasonargyrakis/lara-xtnder
```

## Usage
Once installed
treat it like the make artisan command (no flags for now) 
### Supported types

- string
- bool
- int
- relation (derived via "_id" )
```php
php artisan xtnd:make:all Book "{title:string,author_id:user,is_favorite:bool,likes:int}"
```


### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

-   [Iason Argyrakis](https://github.com/iasonargyrakis)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
