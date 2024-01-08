<?php

use App\Library\Provider\SwooleServerProvider;
use App\Providers\AppProvider;
use App\Providers\RouteServiceProvider;

return [
    'name' => env('APP_NAME', 'buffer'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool)env('APP_DEBUG', false),

    'providers' => [
        RouteServiceProvider::class,
        AppProvider::class,
        SwooleServerProvider::class,
    ],
];
