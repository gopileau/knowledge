<?php
require_once __DIR__.'/../vendor/autoload.php';

// Charger les variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

// Configurer la base de données de test
if (!str_ends_with($_ENV['DB_NAME'], '_test')) {
    $_ENV['DB_NAME'] = $_ENV['DB_NAME'].'_test';
}

// Initialiser l'application
require_once __DIR__.'/../config/config.php';
$dbConfig = require_once __DIR__.'/../config/database.php';

// Initialize PDO connection for tests
global $pdo;
$pdo = $dbConfig['connection'];
