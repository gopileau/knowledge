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
use App\Controllers\AdminController;

session_start();
error_log('Session démarrée avec ID: '.session_id());

$router = new Router();

// Routes
$router->addRoute('GET', '/', fn() => (new HomeController())->index());
$router->addRoute('GET', '/home', fn() => (new HomeController())->index());
$router->addRoute('GET', '/register', fn() => (new AuthController())->register());
$router->addRoute('POST', '/register', fn() => (new AuthController())->handleRegister());
$router->addRoute('GET', '/login', fn() => (new AuthController())->login());
$router->addRoute('POST', '/login', fn() => (new AuthController())->handleLogin());
$router->addRoute('GET', '/logout', fn() => (new AuthController())->logout());
$router->addRoute('GET', '/activate', fn() => (new AuthController())->activate());
$router->addRoute('GET', '/dashboard', fn() => (new HomeController())->dashboard());
$router->addRoute('GET', '/admin/dashboard', fn() => (new AdminController())->dashboard());
$router->addRoute('GET', '/admin/users', fn() => (new AdminController())->users());
$router->addRoute('GET', '/admin/contents', fn() => (new AdminController())->contents());
$router->addRoute('GET', '/admin/orders', fn() => (new AdminController())->orders());
$router->addRoute('GET', '/admin/certifications', fn() => (new AdminController())->certifications());

$router->addRoute('GET', '/admin/contents/new', fn() => (new AdminController())->newCourse());
$router->addRoute('POST', '/admin/contents/create', fn() => (new AdminController())->createCourse());

$router->addRoute('GET', '/admin/contents/edit/(\d+)', function($id) {
    // Determine if id belongs to a course or lesson
    $pdo = new \PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM courses WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $isCourse = $stmt->fetchColumn() > 0;

    $controller = new AdminController();
    if ($isCourse) {
        return $controller->editCourse($id);
    } else {
        return $controller->editLesson($id);
    }
});
$router->addRoute('POST', '/admin/contents/update/(\d+)', function($id) {
    $pdo = new \PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM courses WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $isCourse = $stmt->fetchColumn() > 0;

    $controller = new AdminController();
    if ($isCourse) {
        return $controller->updateCourse($id);
    } else {
        return $controller->updateLesson($id);
    }
});
$router->addRoute('GET', '/admin/contents/delete/(\d+)', function($id) {
    $pdo = new \PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM courses WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $isCourse = $stmt->fetchColumn() > 0;

    $controller = new AdminController();
    if ($isCourse) {
        return $controller->delete($id);
    } else {
        return $controller->deleteLesson($id);
    }
});

$router->addRoute('POST', '/cart/add', fn() => (new \App\Controllers\CartController())->add());

$router->addRoute('GET', '/cart', fn() => (new \App\Controllers\CartController())->index());
$router->addRoute('GET', '/cart/remove/(\d+)', function($id) {
    return (new \App\Controllers\CartController())->remove($id);
});
$router->addRoute('GET', '/cart/clear', fn() => (new \App\Controllers\CartController())->clear());
$router->addRoute('GET', '/checkout', fn() => (new \App\Controllers\CartController())->checkout());

$router->addRoute('POST', '/purchase', fn() => (new \App\Controllers\CartController())->purchase());

// Add profile edit routes
$router->addRoute('GET', '/profile/edit', fn() => (new AuthController())->showEditProfileForm());
$router->addRoute('POST', '/profile/edit', fn() => (new AuthController())->updateProfile());

$router->handle($_SERVER);
