<?php
return [
    'name' => env('APP_NAME', 'buffer'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool)env('APP_DEBUG', false),

    'providers' => [
        \App\Providers\RouteServiceProvider::class,
        \App\Providers\AppProvider::class,
        \App\Library\Provider\SwooleServerProvider::class,
    ],
];
