<?php

return [
    'connections' => [
        "default"=>[
            'read'           => [
                'host' => [
                    env('DB_HOST', '127.0.0.1'),
                ],
            ],
            'write'          => [
                'host' => [
                    env('DB_HOST', '127.0.0.1'),
                ],
            ],
            'driver'         => 'mysql',
            'sticky'         => true,
            'url'            => env('DATABASE_URL'),
            'port'           => env('DB_PORT', '3306'),
            'database'       => env('DB_DATABASE', 'forge'),
            'username'       => env('DB_USERNAME', 'forge'),
            'password'       => env('DB_PASSWORD', ''),
            'unix_socket'    => env('DB_SOCKET', ''),
            'charset'        => env('DB_CHARSET', 'utf8mb4'),
            'collation'      => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix'         => '',
            'prefix_indexes' => true,
            'strict'         => false,
            'engine'         => 'MyISAM',
            'options'        => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ]
    ],

];