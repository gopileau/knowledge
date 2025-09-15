<?php
require_once __DIR__.'/../includes/init.php';
require_once __DIR__.'/../routes/web.php';

use App\Router;

$router = new Router();
$router->handle($_SERVER);
