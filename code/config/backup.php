<?php

return [
    'default' => env('BACKUP_STRATEGY', 'local'),
    
    'strategies' => [
        'local' => [
            'path' => storage_path('backups'),
            'retention_days' => 7,
        ],
        
        's3' => [
            'bucket' => env('BACKUP_S3_BUCKET'),
            'path' => 'backups/',
            'retention_days' => 30,
        ],
    ],
    
    'sources' => [
        'database' => true,
        'storage' => [
            'app/public',
        ],
    ],
    
    'schedule' => [
        'daily_at' => '02:00',
    ],
];
