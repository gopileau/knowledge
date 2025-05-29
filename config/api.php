<?php

return [
    'providers' => [
        'google' => [
            'key' => env('GOOGLE_API_KEY'),
            'secret' => env('GOOGLE_API_SECRET'),
        ],
        
        'stripe' => [
            'key' => env('STRIPE_API_KEY'),
            'secret' => env('STRIPE_API_SECRET'),
        ],
    ],
    
    'rate_limits' => [
        'default' => 100,
        'per_minute' => 60,
    ],
];
