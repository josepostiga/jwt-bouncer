<?php

namespace JosePostiga\JwtBouncer\Tests\Unit\Macros;

use Illuminate\Http\Request;
use JosePostiga\JwtBouncer\Tests\TestCase;
use Lcobucci\JWT\Builder;

class RequestJwtMacroTest extends TestCase
{
    /** @test */
    public function it_can_invoke_macro(): void
    {
        self::assertNull((new Request())->jwt());
    }

    /** @test */
    public function it_returns_the_jwt(): void
    {
        $jwt = (new Builder())->getToken();

        $this->mock(Request::class, static function ($mockedRequest) use ($jwt) {
            $mockedRequest->shouldReceive('jwt')
                ->once()
                ->andReturn($jwt);
        });

        self::assertEquals($jwt, $this->app->make(Request::class)->jwt());
    }
}
