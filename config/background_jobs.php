<?php

return [
    'default_driver' => env('BACKGROUND_JOBS_DRIVER', 'database'),
    
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
    
    'retry_policy' => [
        'max_attempts' => 3,
        'delay' => 60, // en secondes
    ],
    
    'timeout' => 300, // en secondes
];
