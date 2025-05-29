<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuration de la session
error_log('Configuration session:');
error_log('save_path: '.ini_get('session.save_path')); 
error_log('name: '.ini_get('session.name'));
error_log('cookie_params: '.print_r(session_get_cookie_params(), true));

require_once __DIR__.'/../config/config.php';
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../routes/web_temp.php';

session_start();
error_log('Session démarrée avec ID: '.session_id());

$router = new App\Router();

// Routes
$router->addRoute('GET', '/', fn() => (new App\Controllers\HomeController())->index());
$router->addRoute('GET', '/login', fn() => (new App\Controllers\AuthController_temp())->login());
$router->addRoute('POST', '/login', fn() => (new App\Controllers\AuthController_temp())->handleLogin());
// Ajoutez d'autres routes au besoin

$router->handle($_SERVER);
