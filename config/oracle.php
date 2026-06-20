<?php

return [
    'oracle' => [
        'driver' => env('DB_DRIVER', 'oracle'),
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '1521'),
        'database' => env('DB_DATABASE', 'XE'),
        'service_name' => env('DB_SERVICE_NAME'),
        'username' => env('DB_USERNAME'),
        'password' => env('DB_PASSWORD'),
        'charset' => env('DB_CHARSET', 'AL32UTF8'),
        'prefix' => '',
        'prefix_indexes' => true,
    ],
];
