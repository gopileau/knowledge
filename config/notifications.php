<?php

return [
    'default' => env('NOTIFICATION_CHANNEL', 'mail'),
    
    'channels' => [
        'mail' => [
            'driver' => 'mail',
            'queue' => true,
        ],
        
        'database' => [
            'driver' => 'database',
            'table' => 'notifications',
        ],
        
        'broadcast' => [
            'driver' => 'broadcast',
        ],
    ],
];
