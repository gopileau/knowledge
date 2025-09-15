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
    
    'timezone' => env('CRON_TIMEZONE', 'UTC'),
    
    'logging' => [
        'enabled' => true,
        'path' => storage_path('logs/cron.log'),
    ],
];
