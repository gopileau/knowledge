<?php

return [
    'providers' => [
        'stripe' => [
            'test_key' => env('STRIPE_TEST_KEY'),
            'test_secret' => env('STRIPE_TEST_SECRET'),
        ],
        
        'mailgun' => [
            'test_domain' => env('MAILGUN_TEST_DOMAIN'),
            'test_secret' => env('MAILGUN_TEST_SECRET'),
        ],
    ],
    
    'mock_services' => [
        'enabled' => env('MOCK_SERVICES', true),
        'path' => storage_path('mocks'),
    ],
];
