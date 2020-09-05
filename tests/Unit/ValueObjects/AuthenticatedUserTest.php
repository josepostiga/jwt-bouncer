<?php

namespace JosePostiga\JwtBouncer\Tests\Unit\ValueObjects;

use JosePostiga\JwtBouncer\Exceptions\NotImplementedMethodException;
use JosePostiga\JwtBouncer\Tests\TestCase;
use JosePostiga\JwtBouncer\ValueObjects\AuthenticatedUser;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Token;

class AuthenticatedUserTest extends TestCase
{
    private Token $jwt;

    public function notImplementedGetterMethodsProvider(): array
    {
        return [
            'getAuthPassword() method' => ['getAuthPassword'],
            'getRememberToken() method' => ['getRememberToken'],
            'getRememberTokenName() method' => ['getRememberTokenName'],
        ];
    }

    public function notImplementedSetterMethods(): array
    {
        return [
            'setRememberToken() method' => ['setRememberToken'],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->jwt = (new Builder())
            ->relatedTo(1)
            ->withClaim('custom_claim', 'example-custom-claim-value')
            ->getToken();
    }

    /** @test */
    public function it_gets_correct_auth_identifier_name(): void
    {
        $user = new AuthenticatedUser($this->jwt);

        self::assertEquals('sub', $user->getAuthIdentifierName());
    }

    /**
     * @test
     *
     * @dataProvider notImplementedGetterMethodsProvider
     *
     * @param string $method
     */
    public function it_returns_null_for_not_implemented_getter_methods(string $method): void
    {
        $user = new AuthenticatedUser($this->jwt);

        self::assertNull($user->$method());
    }

    /**
     * @test
     *
     * @dataProvider notImplementedSetterMethods
     *
     * @param string $method
     */
    public function it_throws_exception_for_not_implemented_setter_methods(string $method): void
    {
        $this->expectException(NotImplementedMethodException::class);

        (new AuthenticatedUser($this->jwt))->$method(null);
    }

    /** @test */
    public function it_can_get_jwt_custom_claims(): void
    {
        self::assertEquals('example-custom-claim-value', (new AuthenticatedUser($this->jwt))->custom_claim);
    }

    /** @test */
    public function it_can_get_property(): void
    {
        $user = (new AuthenticatedUser($this->jwt));
        $user->property = 'example-property-value';

        self::assertEquals('example-property-value', $user->property);
    }
}
