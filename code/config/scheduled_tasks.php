<?php

return [
    'jobs' => [
        'cleanup' => [
            'command' => 'cleanup:old-records',
            'frequency' => 'daily',
        ],
        
        'notifications' => [
            'command' => 'send:notifications',
            'frequency' => 'hourly',
        ],
    ],
    
    'timezone' => env('SCHEDULED_TASKS_TIMEZONE', 'UTC'),
    
    'logging' => [
        'enabled' => true,
        'path' => storage_path('logs/scheduled_tasks.log'),
    ],
];
