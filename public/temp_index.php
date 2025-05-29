<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
error_log('Configuration session:');
error_log('save_path: '.ini_get('session.save_path')); 
error_log('name: '.ini_get('session.name'));
error_log('cookie_params: '.print_r(session_get_cookie_params(), true));
require_once __DIR__.'/../config/config.php';
require_once __DIR__.'/../vendor/autoload.php';

use App\Router;
use App\Controllers\AuthController;
use App\Controllers\HomeController;

session_start();
error_log('Session démarrée avec ID: '.session_id());

$router = new Router();

$router->addRoute('GET', '/', fn() => (new HomeController())->index());
$router->addRoute('GET', '/home', fn() => (new HomeController())->index());
$router->addRoute('GET', '/register', fn() => (new AuthController())->register());
$router->addRoute('POST', '/register', fn() => (new AuthController())->handleRegister());
$router->addRoute('GET', '/login', fn() => (new AuthController())->login());
$router->addRoute('POST', '/login', fn() => (new AuthController())->handleLogin());
$router->addRoute('GET', '/logout', fn() => (new AuthController())->logout());
$router->addRoute('GET', '/activate', fn() => (new AuthController())->activate());
$router->addRoute('GET', '/dashboard', fn() => (new HomeController())->dashboard());

$router->handle($_SERVER);
