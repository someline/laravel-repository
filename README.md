# Laravel Repository

[![Latest Version](https://img.shields.io/github/release/someline/laravel-repository.svg?style=flat-square)](https://github.com/someline/laravel-repository/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/someline/laravel-repository.svg?style=flat-square)](https://packagist.org/packages/someline/laravel-repository)

Repository design pattern made easy. 

Build for Laravel and [Someline Starter](https://starter.someline.com). 

## Install

### Via Composer

Install composer package to your laravel project

``` bash
composer require someline/laravel-repository
```

Publishing config file. 

``` bash
php artisan vendor:publish --provider "Someline\Repository\Providers\LaravelRepositoryServiceProvider"
```

After published, config file is `config/laravel-repository.php`.

## Testing

``` bash
phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/someline/laravel-repository/blob/master/CONTRIBUTING.md) for details.

## Credits

- [Libern](https://github.com/libern)
- [Laravel](https://github.com/someline)
- [All Contributors](https://github.com/someline/laravel-repository/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
