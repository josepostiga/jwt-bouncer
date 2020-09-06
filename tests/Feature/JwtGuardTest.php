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
            ->withClaim('scopes', ['*'])
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

    /** @test */
    public function it_authorizes_requests_if_required_scope_is_the_all_wildcard(): void
    {
        $this->app['config']->set('jwt-bouncer.scopes', ['*']);

        $this->withToken($this->jwt)
            ->getJson('_test/jwt')
            ->assertOk();
    }

    /** @test */
    public function it_rejects_requests_if_required_scopes_are_not_declared_in_the_jwt(): void
    {
        $this->app['config']->set('jwt-bouncer.scopes', ['another-unrelated-scope', 'specific-scope']);

        $this->withToken($this->jwt)
            ->getJson('_test/jwt')
            ->assertUnauthorized();
    }

    /** @test */
    public function it_authorizes_requests_if_required_scopes_are_declared_in_the_jwt(): void
    {
        $this->app['config']->set('jwt-bouncer.scopes', ['specific-scope']);

        $jwtWithScopes = (new Builder())
            ->relatedTo(1)
            ->withClaim('scopes', ['another-unrelated-scope', 'specific-scope'])
            ->getToken();

        $this->withToken($jwtWithScopes)
            ->getJson('_test/jwt')
            ->assertOk();
    }
}
