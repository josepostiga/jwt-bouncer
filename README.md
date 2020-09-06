# A verification package for your JWT authenticated requests.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/josepostiga/jwt-bouncer.svg?style=flat-square)](https://packagist.org/packages/josepostiga/jwt-bouncer)
![Run tests](https://github.com/josepostiga/jwt-bouncer/workflows/Run%20tests/badge.svg)
[![Coverage Status](https://coveralls.io/repos/github/josepostiga/jwt-bouncer/badge.svg?branch=master)](https://coveralls.io/github/josepostiga/jwt-bouncer?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/josepostiga/jwt-bouncer.svg?style=flat-square)](https://packagist.org/packages/josepostiga/jwt-bouncer)

## Installation

You can install the package via composer:

```bash
composer require josepostiga/jwt-bouncer
```

If you're using a recent Laravel installation, this package is automatically discovered and wired by the framework. 

On Lumen application, we need to manually add the `JosePostiga\JwtBouncer\JwtServiceProvider`.

## Usage

### The JWT auth guard

This package adds a `jwt` api guard to the framework's configuration. You can either explicitly select this guard on a per-route basis or change the default api guard driver to `jwt`, on you `config/auth.php` config file.

### JWT Scopes

This package will validate the `scopes` claim on an incoming request's JWT, and check if the configured scopes are contained in that claim. If not, or if the claim isn't present, the request will be immediately rejected with a `401 Unauthorized` error status code. The same rejection will also happen if the JWT can't be correctly decoded.

### Configuring the default behavior

We can publish the configuration file for the package by running `php artisan vendor:publish --tag=config`. A new `jwt-bouncer.php` config file will be available on the framework's `config` folder. Inside that file, we'll find two main configuration options: `guards` and `scopes`.

The `guards` option contains the necessary structure to be merged to the default `guards` keys on `config/auth.php`, which contains the authentication guards that the framework can use. If we need to rename the driver's key the package should reference to, this is where we'd do it.

The `scopes` key contains an array of pre-defined scopes the guard will be validating on every request's decoded JWT. We can add as many as necessary. **Tip:** If we want to accept all scopes, we'd add the `*` scope, here, which means that all scopes are accepted.

### Testing

This project is fully tested. We have an [automatic pipeline](https://github.com/josepostiga/jwt-bouncer/actions) and an [automatic code quality analysis](https://coveralls.io/github/josepostiga/jwt-bouncer) tool set up to continuously test and assert the quality of all code published in this repository, but you can execute the test suite yourself by running the following command:

``` bash
vendor/bin/phpunit
```

**We aim to keep the master branch always deployable.** Exceptions may happen, but they should be extremely rare.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

Please see [SECURITY](SECURITY.md) for details.

## Credits

- [José Postiga](https://github.com/josepostiga)
- [All Contributors](../../contributors)

## License

Please see [LICENSE](LICENSE.md) for details.
