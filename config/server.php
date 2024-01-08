<?php

return [
    'host' => env('SERVER_HOST', '0.0.0.0'),

    'port' => env('SERVER_PORT', 9505),

    'swoole' => [
        'options' => [
            'log_file' => storage_path('logs' . DIRECTORY_SEPARATOR . 'swoole.log'),
        ],
    ],
];
