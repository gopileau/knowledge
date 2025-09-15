<?php

return [
    'default_driver' => env('BACKGROUND_TASKS_DRIVER', 'database'),
    
    'drivers' => [
        'database' => [
            'table' => 'background_tasks',
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
