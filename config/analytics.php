<?php

return [
    'enabled' => env('ANALYTICS_ENABLED', true),
    
    'providers' => [
        'google' => [
            'tracking_id' => env('GOOGLE_ANALYTICS_ID'),
        ],
        
        'internal' => [
            'storage' => 'database', // ou 'redis'
            'retention_days' => 30,
        ],
    ],
    
    'ignore_routes' => [
        'admin/*',
        'api/documentation',
    ],
];
