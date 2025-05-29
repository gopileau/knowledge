<?php

namespace App\Controllers;

class HomeController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $isLoggedIn = isset($_SESSION['user']);
        $viewPath = realpath(__DIR__.'/../../Views/home.php');
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            throw new \Exception("View file not found: $viewPath");
        }
    }
}
