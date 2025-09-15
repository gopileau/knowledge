<?php
/**
 * Routes – Knowledge Learning
 * ------------------------------------------------------
 * Toutes les routes publiques, protégées, et admin.
 * Framework : micro-router maison / AltoRouter / Bramus, etc.
 * Ajuste la syntaxe des paramètres si ton router diffère.
 */

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\CourseController;
use App\Controllers\CartController;
use App\Controllers\AdminController;

/* =====================================================
 |  AUTHENTIFICATION
 * ===================================================== */
$router->get ('/login',        [AuthController::class, 'showLoginForm']);
$router->post('/login',        [AuthController::class, 'login']);
$router->get ('/register',     [AuthController::class, 'register']);
$router->post('/register',     [AuthController::class, 'handleRegister']);
$router->post('/logout',       [AuthController::class, 'logout']);   // form POST avec token CSRF

/* =====================================================
 |  DASHBOARD UTILISATEUR
 * ===================================================== */
$router->get('/',              [HomeController::class, 'dashboard']);  // redirigé ou même méthode
$router->get('/dashboard',     [HomeController::class, 'dashboard']);

/* =====================================================
 |  COURS & LEÇONS
 * ===================================================== */
$router->get ('/lesson/(\d+)',         [CourseController::class, 'showLesson']);      // ex: /lesson/12
$router->post('/lessons/validate',     [CourseController::class, 'validateLesson']);  // AJAX/POST
$router->get ('/my-lessons',           [CourseController::class, 'showUserLessons']);

/* =====================================================
 |  PANIER & CHECKOUT
 * ===================================================== */
$router->get('/cart',                 [CartController::class, 'index']);
$router->get('/cart/remove/(\d+)',    [CartController::class, 'remove']);  // suppr. un item
$router->get('/cart/clear',           [CartController::class, 'clear']);   // vide le panier
$router->get('/checkout',             [CartController::class, 'checkout']); // sandbox paiement

/* =====================================================
 |  PROFIL UTILISATEUR
 * ===================================================== */
$router->get ('/profile/edit',  [AuthController::class, 'showEditProfileForm']);
$router->post('/profile/edit',  [AuthController::class, 'updateProfile']);

/* =====================================================
 |  ADMIN – protégé par auth + rôle « admin »
 * ===================================================== */
require_once __DIR__.'/../includes/auth.php';

$authMiddleware = function($request) {
    requireAuth();
    return true;
};

$adminMiddleware = function($request) {
    requireAdmin();
    return true;
};

$router->addRouteWithMiddleware('GET', '/admin/dashboard', [AdminController::class, 'dashboard'], [$authMiddleware, $adminMiddleware]);

// Ajout d'une route pour /admin/ qui redirige vers /admin/dashboard
$router->get('/admin', function() {
    header('Location: /admin/dashboard');
    exit;
});

// → ajoute d’autres routes admin ici (users, courses, orders, certifications…)
// $router->get('/admin/users',   [...])->middleware(['auth','admin']);
// $router->post('/admin/users',  [...])->middleware(['auth','admin']);
