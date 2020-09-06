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
    private array $scopes;

    public function __construct(?Token $jwt)
    {
        $this->jwt = $jwt;
        $this->scopes = config('jwt-bouncer.scopes');
    }

    public function user(): ?Authenticatable
    {
        if (! is_null($this->user) && ! $this->jwt->isExpired()) {
            return $this->user;
        }

        if ($this->jwt === null || ! $this->jwtContainsCorrectScopes()) {
            return null;
        }

        return $this->user = new AuthenticatedUser($this->jwt);
    }

    public function validate(array $credentials = [])
    {
        throw new NotImplementedMethodException('Method not implemented.');
    }

    private function jwtContainsCorrectScopes(): bool
    {
        return $this->jwt->hasClaim('scopes')
            && collect($this->jwt->getClaim('scopes'))
                ->filter(fn ($scope) => in_array($scope, $this->scopes, true))
                ->isNotEmpty();
    }
}
