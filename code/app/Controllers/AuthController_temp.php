<?php

namespace App\Controllers;

use App\Models\User_temp as User;

class AuthController {
    protected function render($view, $data = []) {
        $viewPath = realpath(__DIR__.'/../../Views/'.$view.'.php');
        if (!file_exists($viewPath)) {
            throw new \Exception("View file not found: $view");
        }

        extract($data);
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require_once __DIR__.'/../../Views/layout/header.php';
        require_once $viewPath;
        require_once __DIR__.'/../../Views/layout/footer.php';
    }

    public function register() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        
        $this->render('auth/register', [
            'title' => 'Inscription',
            'errors' => $_SESSION['errors'] ?? [],
            'old' => $_SESSION['old'] ?? [],
            'csrf_token' => $_SESSION['csrf_token']
        ]);
        unset($_SESSION['errors']);
        unset($_SESSION['old']);
    }

    public function handleRegister() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // CSRF protection
        if (empty($_POST['_token']) || $_POST['_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['errors'] = ['Invalid CSRF token'];
            header('Location: /register');
            exit;
        }

        // Validation
        $required = ['email', 'password', 'password_confirm'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['errors'][] = "Field $field is required";
            }
        }

        if ($_POST['password'] !== $_POST['password_confirm']) {
            $_SESSION['errors'][] = "Passwords don't match";
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['errors'][] = "Invalid email format";
        }

        if (isset($_SESSION['errors'])) {
            $_SESSION['old'] = $_POST;
            header('Location: /register');
            exit;
        }

        // Register user
        try {
            $userModel = new User();
            $activationToken = $userModel->register(
                $_POST['email'],
                $_POST['password']
            );
            
            // Envoi de l'email d'activation
            $activationLink = "http://" . $_SERVER['HTTP_HOST'] . "/activate?token=" . $activationToken;
            $to = $_POST['email'];
            $subject = "Activez votre compte Knowledge Learning";
            $message = "Bonjour,\n\nMerci pour votre inscription. Veuillez cliquer sur le lien suivant pour activer votre compte :\n\n" . $activationLink;
            $headers = "From: no-reply@knowledge-learning.com";
            
            if (mail($to, $subject, $message, $headers)) {
                $_SESSION['success'] = 'Inscription réussie ! Veuillez vérifier vos emails pour activer votre compte.';
            } else {
                $_SESSION['errors'] = ["L'inscription a réussi mais l'email d'activation n'a pas pu être envoyé."];
            }
            header('Location: /login');
            exit;
        } catch (\Exception $e) {
            $_SESSION['errors'] = [$e->getMessage()];
            $_SESSION['old'] = $_POST;
            header('Location: /register');
            exit;
        }
    }

    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        
        $this->render('auth/login', [
            'title' => 'Connexion', 
            'errors' => $_SESSION['errors'] ?? [],
            'csrf_token' => $_SESSION['csrf_token']
        ]);
        unset($_SESSION['errors']);
    }

    public function handleLogin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // CSRF protection
        if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['errors'] = ['Invalid CSRF token'];
            header('Location: /login');
            exit;
        }

        // Validation
        if (empty($_POST['email']) || empty($_POST['password'])) {
            $_SESSION['errors'] = ['Email and password required'];
            header('Location: /login');
            exit;
        }

        // Authenticate using User model
        error_log('Tentative de connexion pour: '.$_POST['email']);
        $userModel = new User();
        
        try {
            $user = $userModel->login($_POST['email'], $_POST['password']);
            error_log('Utilisateur trouvé: '.print_r($user, true));
            
            // Vérifiez si l'utilisateur est activé
            if (!$user['is_activated']) {
                error_log('Échec de connexion - Compte non activé pour: '.$_POST['email']);
                $_SESSION['errors'] = ['Échec de connexion - Compte non activé'];
                header('Location: /login');
                exit;
            }
            
            // Set session
            $_SESSION['user'] = [
                'id' => $user['id'],
                'email' => $user['email'],
                'role' => $user['role'],
                'logged_in' => true
            ];
            
            error_log('Session utilisateur définie pour: '.$user['email']);
            
            // Redirect based on role
            if ($user['role'] === 'admin') {
                header('Location: /admin/dashboard.php');
            } else {
                header('Location: /public/index.php');
            }
            exit;
        } catch (\Exception $e) {
            error_log('Erreur lors de la connexion: '.$e->getMessage());
            $_SESSION['errors'] = ['Erreur: '.$e->getMessage()];
            header('Location: /login');
            exit;
        }
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: /');
        exit;
    }

    public function activate() {
        // Logique d'activation
    }
}
