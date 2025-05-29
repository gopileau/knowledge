<?php

require_once __DIR__ . '/config.php';

// Configuration array (maintained for compatibility)
$config = [
    'default' => 'mysql',
    
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => DB_HOST,
            'port' => '3306',
            'database' => DB_NAME,
            'username' => DB_USER,
            'password' => DB_PASS,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
    ],

    'migrations' => 'migrations'
];

// Create PDO connection
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

return [
    'config' => $config,
    'connection' => $pdo
];
