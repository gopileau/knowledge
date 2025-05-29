<?php
require_once dirname(__DIR__).'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'knowledge_learning');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');

// Configuration de l'application
define('APP_NAME', 'Knowledge Learning');
define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost');
define('APP_DEBUG', filter_var($_ENV['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOLEAN));
