<?php

namespace App\Controllers;

class AuthController {
    
    // Rendre la vue avec les données
    protected function render($view, $data = []) {
        $viewPath = realpath(__DIR__.'/../../Views/'.$view.'.php');
        if (!file_exists($viewPath)) {
            throw new \Exception("View file not found: $view");
        }

        extract($data);

        // Assurer que la session est démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require_once __DIR__.'/../../Views/layout/header.php';
        require_once $viewPath;
        require_once __DIR__.'/../../Views/layout/footer.php';
    }

    // Méthode pour afficher la vue d'inscription
    public function register() {
        $this->render('auth/register', [
            'title' => 'Inscription',
            'errors' => $_SESSION['errors'] ?? [],
            'old' => $_SESSION['old_input'] ?? [] // Revenir aux valeurs précédemment soumises
        ]);
        unset($_SESSION['errors'], $_SESSION['old_input']);
    }

    // Méthode pour afficher la vue de connexion
    public function login() {
        $this->render('auth/login', [
            'title' => 'Connexion',
            'errors' => $_SESSION['errors'] ?? []
        ]);
        unset($_SESSION['errors']);
    }

    // Méthode pour déconnecter l'utilisateur
    public function logout() {
        session_start();
        session_destroy();
        header('Location: /');
        exit;
    }

    // Méthode pour gérer l'inscription
    public function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifier si le token CSRF est valide
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $_SESSION['errors'] = ['Token CSRF invalide'];
                header('Location: /register');
                exit;
            }
    
            // Supprimer le token CSRF après l'utilisation
            unset($_SESSION['csrf_token']);
    
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;
            $confirmPassword = $_POST['confirm_password'] ?? null;
    
            if (!$email || !$password || !$confirmPassword) {
                $_SESSION['errors'] = ['Veuillez remplir tous les champs.'];
                $_SESSION['old_input'] = $_POST;
                header('Location: /register');
                exit;
            }
    
            // Vérification si les mots de passe correspondent
            if ($password !== $confirmPassword) {
                $_SESSION['errors'] = ['Les mots de passe ne correspondent pas.'];
                $_SESSION['old_input'] = $_POST;
                header('Location: /register');
                exit;
            }
    
            require __DIR__ . '/../../config/config.php'; // Inclure la config DB
    
            // Vérifier si l'email est déjà utilisé
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();
    
            if ($user) {
                $_SESSION['errors'] = ['Cet email est déjà utilisé.'];
                $_SESSION['old_input'] = $_POST;
                header('Location: /register');
                exit;
            }
    
            // Hacher le mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
            // Insérer l'utilisateur dans la base de données
            try {
                $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
                $stmt->execute([
                    'email' => $email,
                    'password' => $hashedPassword
                ]);
                $_SESSION['success'] = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
                header('Location: /login');
            } catch (\PDOException $e) {
                $_SESSION['errors'] = ['Erreur lors de l\'inscription : ' . $e->getMessage()];
                header('Location: /register');
            }
            exit;
        }
    }
    

    // Méthode pour gérer la connexion
    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;

            require __DIR__ . '/../../config/config.php'; // Inclure la config DB

            // Vérifier si l'utilisateur existe dans la DB
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user'] = $user; // Stocker l'utilisateur dans la session
                header('Location: /dashboard');
            } else {
                $_SESSION['errors'] = ['Email ou mot de passe incorrect'];
                header('Location: /login');
            }
            exit;
        }
    }
}
