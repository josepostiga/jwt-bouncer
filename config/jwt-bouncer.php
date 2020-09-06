<?php

return [
    'guards' => [
        'jwt' => [
            'driver' => 'jwt',
        ],
    ],
    'scopes' => env('JWT_SCOPES', ['*']),
];
