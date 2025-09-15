<?php

return [
    'tasks' => [
        'cleanup' => [
            'command' => 'cleanup:old-records',
            'frequency' => 'daily',
        ],
        
        'notifications' => [
            'command' => 'send:notifications',
            'frequency' => 'hourly',
        ],
    ],
    
    'timezone' => env('SCHEDULER_TIMEZONE', 'UTC'),
    
    'logging' => [
        'enabled' => true,
        'path' => storage_path('logs/scheduler.log'),
    ],
];
