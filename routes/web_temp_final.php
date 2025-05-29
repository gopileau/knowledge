<?php

use App\Router;
use App\Controllers\AuthController_temp as AuthController;
use App\Controllers\HomeController;
use App\Controllers\AdminController_temp as AdminController;
use App\Controllers\CourseController;

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

// Route pour les cours
$router->addRoute('GET', '/courses', fn() => (new CourseController())->showCourses());
$router->addRoute('POST', '/courses/purchase', fn() => (new CourseController())->purchaseCourse($_POST['course_id']));
$router->addRoute('POST', '/lessons/validate', fn() => (new CourseController())->validateLesson($_POST['lesson_id'])); 
$router->addRoute('GET', '/certificates/issue', fn() => (new CourseController())->issueCertificate($_SESSION['user']['id']));

$router->handle($_SERVER);
