<?php

return [
    'default' => env('PUSH_SERVICE', 'firebase'),
    
    'services' => [
        'firebase' => [
            'key' => env('FIREBASE_API_KEY'),
            'project_id' => env('FIREBASE_PROJECT_ID'),
        ],
        
        'onesignal' => [
            'app_id' => env('ONESIGNAL_APP_ID'),
            'api_key' => env('ONESIGNAL_API_KEY'),
        ],
    ],
    
    'channels' => [
        'broadcast' => [
            'driver' => 'broadcast',
        ],
        
        'database' => [
            'driver' => 'database',
        ],
    ],
];
