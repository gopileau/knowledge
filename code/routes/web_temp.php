<?php

use App\Router;
use App\Controllers\AuthController_temp;
use App\Controllers\HomeController;

$router = new Router();

$router->addRoute('GET', '/', fn() => (new HomeController())->index());
$router->addRoute('GET', '/home', fn() => (new HomeController())->index());
$router->addRoute('GET', '/register', fn() => (new AuthController_temp())->register());
$router->addRoute('POST', '/register', fn() => (new AuthController_temp())->handleRegister());
$router->addRoute('GET', '/login', fn() => (new AuthController_temp())->login());
$router->addRoute('POST', '/login', fn() => (new AuthController_temp())->handleLogin());
$router->addRoute('GET', '/logout', fn() => (new AuthController_temp())->logout());
$router->addRoute('GET', '/activate', fn() => (new AuthController_temp())->activate());
$router->addRoute('GET', '/dashboard', fn() => (new HomeController())->dashboard());

$router->handle($_SERVER);
