<?php

namespace JosePostiga\JwtBouncer\Guards;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use JosePostiga\JwtBouncer\Exceptions\NotImplementedMethodException;
use JosePostiga\JwtBouncer\ValueObjects\AuthenticatedUser;
use Lcobucci\JWT\Token;

class JwtGuard implements Guard
{
    use GuardHelpers;

    private ?Token $jwt;

    public function __construct(?Token $jwt)
    {
        $this->jwt = $jwt;
    }

    public function user(): ?Authenticatable
    {
        if (! is_null($this->user) && ! $this->jwt->isExpired()) {
            return $this->user;
        }

        if ($this->jwt === null) {
            return null;
        }

        return $this->user = new AuthenticatedUser($this->jwt);
    }

    public function validate(array $credentials = [])
    {
        throw new NotImplementedMethodException('Method not implemented.');
    }
}
