<?php

namespace JosePostiga\JwtBouncer;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use JosePostiga\JwtBouncer\Guards\JwtGuard;
use Lcobucci\JWT\Configuration;

class JwtBouncerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/jwt-bouncer.php', 'jwt-bouncer');

        $this->extendAuthGuards();

        $this->publishesAssets();
    }

    private function extendAuthGuards(): void
    {
        $config = $this->app['config'];

        $config->set(
            'auth.guards',
            array_merge($config->get('auth.guards', []), $config->get('jwt-bouncer.guards'))
        );

        $this->app['auth']->extend(
            $config->get('jwt-bouncer.guards.jwt.driver'),
            static function (Container $app, $name, array $config) {
                /** @var Configuration $config */
                $config = $app->make(Configuration::class);
                try {
                    $jwt = $config->parser()->parse($app['request']->bearerToken());
                } catch (\Exception $exception) {
                    $jwt = null;
                }

                return new JwtGuard($jwt);
            }
        );
    }

    private function publishesAssets(): void
    {
        $this->publishes([
            __DIR__.'/../config/jwt-bouncer.php' => $this->app->configPath('jwt-bouncer.php'),
        ], 'config');
    }
}
