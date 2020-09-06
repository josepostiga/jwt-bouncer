<?php

namespace JosePostiga\JwtBouncer;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use JosePostiga\JwtBouncer\Guards\JwtGuard;
use Lcobucci\JWT\Parser;

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
            static function (Application $app, $name, array $config) {
                try {
                    $jwt = (new Parser())->parse($app['request']->bearerToken());
                } catch (\Exception $exception) {
                    $jwt = null;
                }

                return new JwtGuard($jwt);
            }
        );
    }

    private function publishesAssets(): void
    {
        if (strpos('Lumen', $this->app->version()) === 0) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/jwt-bouncer.php' => config_path('jwt-bouncer.php'),
        ], 'config');
    }
}
