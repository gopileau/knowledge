<?php

return [
    'enabled' => env('PERFORMANCE_MONITORING', true),
    
    'metrics' => [
        'response_time' => [
            'threshold' => 500, // en millisecondes
        ],
        
        'memory_usage' => [
            'threshold' => '128M',
        ],
        
        'database_queries' => [
            'threshold' => 100,
        ],
    ],

    'storage' => [
        'driver' => env('PERF_STORAGE_DRIVER', 'file'),
        'path' => storage_path('performance'),
    ],

    'alerting' => [
        'email' => env('PERF_ALERT_EMAIL'),
        'slack' => env('PERF_ALERT_SLACK_WEBHOOK'),
    ],
];
