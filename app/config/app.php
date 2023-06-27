<?php
return [
    'name' => env('APP_NAME', 'buffer'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool)env('APP_DEBUG', false),

    'providers' => [
        \App\Library\Provider\SwooleServerProvider::class,
        \App\Library\Provider\RouteServerProvider::class,
        \App\Providers\AppProvider::class,
    ],
];
