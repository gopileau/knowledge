<?php

return [
    'global' => [
        // Middlewares globaux
        \App\Http\Middleware\TrimStrings::class,
        \App\Http\Middleware\TrustProxies::class,
    ],
    
    'route' => [
        // Middlewares par route
        'auth' => \App\Http\Middleware\Authenticate::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ],
    
    'priority' => [
        // Ordre d'exécution des middlewares
        \App\Http\Middleware\StartSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
    ],
];
