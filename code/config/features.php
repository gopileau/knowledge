<?php

return [
    'beta' => [
        'new_dashboard' => env('FEATURE_NEW_DASHBOARD', false),
        'ai_assistant' => env('FEATURE_AI_ASSISTANT', false),
    ],
    
    'experimental' => [
        'voice_commands' => env('FEATURE_VOICE_COMMANDS', false),
        'virtual_reality' => env('FEATURE_VIRTUAL_REALITY', false),
    ],
    
    'access' => [
        'beta_testers' => [
            'user1@example.com',
            'user2@example.com',
        ],
    ],
];
