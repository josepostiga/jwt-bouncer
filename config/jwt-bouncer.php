<?php

return [
    'guards' => [
        'jwt' => [
            'driver' => 'jwt',
        ],
    ],
    'scopes' => explode(',', env('JWT_SCOPES', '*')),
];
