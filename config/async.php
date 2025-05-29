<?php

return [
    'default_driver' => env('ASYNC_DRIVER', 'database'),
    
    'drivers' => [
        'database' => [
            'table' => 'async_jobs',
            'connection' => null,
        ],
        
        'redis' => [
            'connection' => 'default',
            'queue' => 'async',
        ],
    ],
    
    'retry_policy' => [
        'max_attempts' => 3,
        'delay' => 60, // en secondes
    ],
    
    'timeout' => 300, // en secondes
];
