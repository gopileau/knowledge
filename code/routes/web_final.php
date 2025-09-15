<?php

use App\Router;
use App\Controllers\AuthController_temp as AuthController;
use App\Controllers\HomeController;
use App\Controllers\AdminController_temp as AdminController;

$router = new Router();

$router->addRoute('GET', '/', fn() => (new HomeController())->index());
$router->addRoute('GET', '/home', fn() => (new HomeController())->index());
$router->addRoute('GET', '/register', fn() => (new AuthController())->register());
$router->addRoute('POST', '/register', fn() => (new AuthController())->handleRegister());
$router->addRoute('GET', '/login', fn() => (new AuthController())->login());
$router->addRoute('POST', '/login', fn() => (new AuthController())->handleLogin());
$router->addRoute('GET', '/logout', fn() => (new AuthController())->logout());
$router->addRoute('GET', '/activate', fn() => (new AuthController())->activate());
// Routes Admin
$router->addRoute('GET', '/admin/dashboard', fn() => (new AdminController())->dashboard());
$router->addRoute('GET', '/admin/users', fn() => (new AdminController())->users());
$router->addRoute('GET', '/admin/contents', fn() => (new AdminController())->contents());
$router->addRoute('GET', '/admin/orders', fn() => (new AdminController())->orders());
$router->addRoute('GET', '/admin/certifications', fn() => (new AdminController())->certifications());

// Route dashboard utilisateur standard
$router->addRoute('GET', '/dashboard', fn() => (new HomeController())->dashboard());

$router->handle($_SERVER);
