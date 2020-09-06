<?php

namespace JosePostiga\JwtBouncer\ValueObjects;

use Illuminate\Contracts\Auth\Authenticatable;
use JosePostiga\JwtBouncer\Exceptions\NotImplementedMethodException;
use Lcobucci\JWT\Token;

class AuthenticatedUser implements Authenticatable
{
    private Token $token;

    public function __construct(Token $token)
    {
        $this->token = $token;
    }

    public function getAuthIdentifierName(): string
    {
        return 'sub';
    }

    public function getAuthIdentifier()
    {
        return $this->token->getClaim($this->getAuthIdentifierName());
    }

    public function getAuthPassword(): ?string
    {
        return null;
    }

    public function getRememberToken(): ?string
    {
        return null;
    }

    public function setRememberToken($value): void
    {
        throw new NotImplementedMethodException('Method not implemented.');
    }

    public function getRememberTokenName(): ?string
    {
        return null;
    }

    public function __get($claim)
    {
        return $this->token->getClaim($claim);
    }
}
