<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user']) && $_SESSION['user']['logged_in'];
}

function hasRole($role) {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === $role;
}

function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: /auth/login.php');
        exit;
    }
}

function requireAdmin() {
    requireAuth();
    if (!hasRole('admin')) {
        header('HTTP/1.1 403 Forbidden');
        echo "Accès refusé : permissions insuffisantes";
        exit;
    }
}

function verifyJWT() {
    if (empty($_COOKIE['jwt'])) {
        header('Location: /auth/login.php');
        exit;
    }

    try {
        $decoded = \Firebase\JWT\JWT::decode($_COOKIE['jwt'], $_ENV['JWT_SECRET'], ['HS256']);
        $_SESSION['user'] = [
            'id' => $decoded->sub,
            'role' => $decoded->role,
            'logged_in' => true
        ];
    } catch (\Exception $e) {
        header('Location: /auth/login.php');
        exit;
    }
}
?>
