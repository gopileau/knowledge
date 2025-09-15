<?php

return [
    'default' => env('APP_LANG', 'fr'),
    
    'paths' => [
        resource_path('lang'),
    ],

    'locale' => 'fr',
    
    'fallback_locale' => 'en',
    
    'available_locales' => ['fr', 'en', 'es'],
    
    'translation_files' => [
        'auth',
        'validation',
        'courses',
    ],
];
