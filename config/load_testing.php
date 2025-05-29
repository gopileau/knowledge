<?php

return [
    'enabled' => env('LOAD_TESTING_ENABLED', false),
    
    'scenarios' => [
        'default' => [
            'concurrency' => 100,
            'duration' => '5m',
            'ramp_up' => '30s',
        ],
    ],
    
    'thresholds' => [
        'response_time' => [
            'warning' => 500, // ms
            'critical' => 1000, // ms
        ],
        'error_rate' => [
            'warning' => 1, // %
            'critical' => 5, // %
        ],
    ],
    
    'reporting' => [
        'storage' => storage_path('load_tests'),
        'retention' => '7d',
    ],
];
