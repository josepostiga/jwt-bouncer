<?php

namespace JosePostiga\JwtBouncer\Tests\Feature;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use JosePostiga\JwtBouncer\Tests\TestCase;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Token;

class JwtGuardTest extends TestCase
{
    private Token $jwt;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('auth:jwt')
            ->get('_test/jwt', static function () {
            });

        $this->jwt = (new Builder())
            ->relatedTo(1)
            ->getToken();
    }

    /** @test */
    public function unauthenticated_requests_to_protected_routes_are_rejected(): void
    {
        $this->getJson('_test/jwt')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function authenticated_requests_to_protected_routes_are_processed(): void
    {
        $this->withToken($this->jwt)
            ->getJson('_test/jwt')
            ->assertOk();
    }
}
