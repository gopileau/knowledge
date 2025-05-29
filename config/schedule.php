<?php

return [
    'tasks' => [
        // Exemple de tâche planifiée
        // 'cleanup' => [
        //     'command' => 'cleanup:old-records',
        //     'frequency' => 'daily',
        // ],
    ],
    
    'timezone' => env('APP_TIMEZONE', 'UTC'),
    
    'maintenance' => [
        'enabled' => false,
        'message' => 'Maintenance en cours',
    ],
];
