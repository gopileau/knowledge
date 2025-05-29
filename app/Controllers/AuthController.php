<?php

namespace App\Controllers;

use App\Models\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
        if (session_status() === PHP_SESSION_NONE) session_start();

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $this->render('auth/register', [
            'title' => 'Inscription',
            'errors' => $_SESSION['errors'] ?? [],
            'old' => $_SESSION['old'] ?? [],
            'csrf_token' => $_SESSION['csrf_token']
        ]);

        unset($_SESSION['errors'], $_SESSION['old']);
    }

    public function handleRegister() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (empty($_POST['_token']) || $_POST['_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['errors'] = ['Jeton CSRF invalide'];
            header('Location: /register');
            exit;
        }

        $required = ['email', 'password', 'password_confirm'];
        $_SESSION['errors'] = [];

        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['errors'][] = "Le champ $field est requis.";
            }
        }

        if ($_POST['password'] !== $_POST['password_confirm']) {
            $_SESSION['errors'][] = "Les mots de passe ne correspondent pas.";
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['errors'][] = "Adresse email invalide.";
        }

        if (!empty($_SESSION['errors'])) {
            $_SESSION['old'] = $_POST;
            header('Location: /register');
            exit;
        }

        try {
            $userModel = new User();
            $activationToken = $userModel->register($_POST['email'], $_POST['password']);

            $activationLink = "http://" . $_SERVER['HTTP_HOST'] . "/activate?token=" . $activationToken;

            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host       = 'localhost'; // Utilisation de MailHog en local
                $mail->SMTPAuth   = false;
                $mail->SMTPSecure = false;
                $mail->Port       = 1025;

                //Recipients
                $mail->setFrom('no-reply@knowledge-learning.com', 'Knowledge Learning');
                $mail->addAddress($_POST['email']);

                // Content
                $mail->isHTML(false);
                $mail->Subject = 'Activez votre compte Knowledge Learning';
                $mail->Body    = "Bonjour,\n\nMerci pour votre inscription. Cliquez sur le lien pour activer votre compte :\n$activationLink";

                $mail->send();
                $_SESSION['success'] = 'Inscription réussie ! Vérifiez votre email pour activer votre compte.';
            } catch (Exception $e) {
                $_SESSION['errors'] = ["Inscription réussie mais l'email n'a pas pu être envoyé. Mailer Error: {$mail->ErrorInfo}"];
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
        if (session_status() === PHP_SESSION_NONE) session_start();

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $this->render('auth/login', [
            'title' => 'Connexion',
            'errors' => $_SESSION['errors'] ?? [],
            'csrf_token' => $_SESSION['csrf_token']
        ]);

        unset($_SESSION['errors']);
    }

    public function handleLogin() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['errors'] = ['Jeton CSRF invalide'];
            header('Location: /login');
            exit;
        }

        if (empty($_POST['email']) || empty($_POST['password'])) {
            $_SESSION['errors'] = ['Email et mot de passe requis'];
            header('Location: /login');
            exit;
        }

        try {
            $userModel = new User();
            $user = $userModel->login($_POST['email'], $_POST['password']);

            if (!$user) {
                $_SESSION['errors'] = ['Identifiants incorrects ou compte non activé.'];
                header('Location: /login');
                exit;
            }

            $_SESSION['user'] = [
                'id' => $user['id'],
                'email' => $user['email'],
                'role' => $user['role'],
                'logged_in' => true
            ];

            setcookie('jwt', $user['jwt'], time() + 3600, '/', '', true, true);

$redirect = $user['role'] === 'admin'
    ? '/admin/dashboard'
    : '/dashboard';

header('Location: ' . $redirect);
            exit;

        } catch (\Exception $e) {
            $_SESSION['errors'] = ['Erreur de connexion: ' . $e->getMessage()];
            header('Location: /login');
            exit;
        }
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        setcookie('jwt', '', time() - 3600, '/', '', true, true);
        header('Location: /');
        exit;
    }

    public function activate() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (empty($_GET['token'])) {
            $_SESSION['errors'] = ["Lien d'activation invalide"];
            header('Location: /register');
            exit;
        }

        $userModel = new User();
        if ($userModel->activateAccount($_GET['token'])) {
            $_SESSION['success'] = 'Compte activé avec succès !';
            header('Location: /login');
        } else {
            $_SESSION['errors'] = ['Token invalide ou expiré.'];
            header('Location: /register');
        }
        exit;
    }

    public function showEditProfileForm() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $userModel = new User();
        $user = $userModel->getUserById($_SESSION['user']['id']);

        $this->render('auth/edit_profile', [
            'user' => $user,
            'errors' => $_SESSION['errors'] ?? [],
            'success' => $_SESSION['success'] ?? null,
        ]);

        unset($_SESSION['errors'], $_SESSION['success']);
    }

    public function updateProfile() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        if (empty($_POST['_token']) || $_POST['_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['errors'] = ['Jeton CSRF invalide'];
            header('Location: /profile/edit');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        $errors = [];

        if (empty($name)) {
            $errors[] = "Le nom est requis.";
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Adresse email invalide.";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: /profile/edit');
            exit;
        }

        $userModel = new User();

        try {
            $userModel->updateName($userId, $name);
            $userModel->updateEmail($userId, $email);

            $_SESSION['success'] = "Profil mis à jour avec succès.";

            // Update session user data
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['email'] = $email;

            header('Location: /profile/edit');
            exit;
        } catch (\Exception $e) {
            $_SESSION['errors'] = ["Erreur lors de la mise à jour du profil: " . $e->getMessage()];
            header('Location: /profile/edit');
            exit;
        }
    }
}
