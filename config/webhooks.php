<?php

return [
    'enabled' => env('WEBHOOKS_ENABLED', true),
    
    'endpoints' => [
        'stripe' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'events' => [
                'payment_succeeded',
                'payment_failed',
            ],
        ],
        
        'github' => [
            'secret' => env('GITHUB_WEBHOOK_SECRET'),
            'events' => [
                'push',
                'pull_request',
            ],
        ],
    ],
    
    'retry_policy' => [
        'max_attempts' => 3,
        'delay' => 60, // en secondes
    ],
];
