<?php

return [
    'default_driver' => env('BACKGROUND_DRIVER', 'database'),
    
    'drivers' => [
        'database' => [
            'table' => 'background_jobs',
            'connection' => null,
        ],
        
        'redis' => [
            'connection' => 'default',
            'queue' => 'background',
        ],
    ],
    
    'retry_after' => 90,
    'timeout' => 60,
    'max_attempts' => 3,
];
