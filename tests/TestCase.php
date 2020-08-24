<?php

namespace JosePostiga\JwtBouncer\Tests;

use JosePostiga\JwtBouncer\JwtBouncerServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            JwtBouncerServiceProvider::class,
        ];
    }
}
