# A JWT authorization guard for your Laravel/Lumen apps.

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

### Configuration

If we're using Laravel, we can publish the configuration file for the package by running `php artisan vendor:publish --tag=config`. A new `jwt-bouncer.php` config file will be available on the framework's `config` folder. Inside that file, we'll find two main configuration options: `guards` and `scopes`.

* The `guards` option contains the necessary structure to be merged to the default `guards` keys on `config/auth.php`, which contains the authentication guards that the framework can use. If we need to rename the driver's key the package should reference to, this is where we'd do it.

* The `scopes` key contains an array of pre-defined scopes the guard will be validating on every request's decoded JWT. We can add as many as necessary. **Tip:** If we want to accept all scopes, we'd add the `*` scope, here, which means that all scopes are accepted.

If we're using Lumen, then things get a little more tricky. We need to add a `JWT_SCOPES` key on the `.env` file, where we defined all the scopes we accept separated by a comma. We also need to add the auth configuration file load call in the `bootstrap/app.php` file, by adding `$app->configure('auth')` on the configuration files load section, there.

### AuthServiceProvider.php
Ensure to bind your public/secret key, for example:
```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
       $this->app->bind(Configuration::class, function () {
            $jwtSecret = InMemory::file(
                base_path('private_key.pem'),
                env('JWT_PRIVATE_PASSPHRASE', '1234')
            );

            $jwtPublic = InMemory::file(
                base_path('public_key.pem')
            );
            return Configuration::forAsymmetricSigner(
                new Sha256,
                $jwtSecret,
                $jwtPublic
            );
        });
    }

}

```

>You can read more about how to use the `Configuration` class here: [https://lcobucci-jwt.readthedocs.io/en/latest/configuration/](https://lcobucci-jwt.readthedocs.io/en/latest/configuration/)

### Protecting routes

After executing the configuration steps, we can call the `auth:jwt` middleware on any route, or route group, to use this package's guard.

### The `Authenticatable` user instance

On a general Laravel application, we have access to the authenticated user instance via the `Auth::user()` or `request->user()`. This instance is, generally speaking, an instance of an Eloquent model or, in some cases, a resource from a users-like database table.

When using this package's JWT guard, we'll also have access to the authenticated user, but it won't be any of the types described before. Instead, it'll be an instance of the `AuthenticatedUser` value object. This class implements the `Authenticatable` interface, but its source of data is the JWT itself.

This means that calling `Auth::user()->id()` will return the value of the JWT's `sub` claim. If we want to access any other claim in the JWT, we only need to reference it by its key name, so if we have a `name` claim, we can access it with `Auth::user()->name`. All calls to property access will be routed to the JWT's claims.

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
