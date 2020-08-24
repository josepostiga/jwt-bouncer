<?php

namespace JosePostiga\JwtBouncer\Macros;

use Illuminate\Http\Request;
use Lcobucci\JWT\Parser;

class RequestJwtMacro
{
    public function __invoke()
    {
        try {
            $jwt = (new Parser())->parse(request()->bearerToken());
        } catch (\Exception $exception) {
            return null;
        }

        if ($jwt->isExpired()) {
            return null;
        }

        return $jwt;
    }
}
