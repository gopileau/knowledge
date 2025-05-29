<?php

namespace App\Controllers;

use App\Models\User_temp; // Assurez-vous d'utiliser le bon modèle
require_once __DIR__.'/../includes/auth.php';
require_once __DIR__.'/../includes/db.php';

class AdminController {
    public function dashboard() {
        // Logique pour afficher le tableau de bord
        include __DIR__.'/../Views/admin/dashboard.php';
    }

    public function users() {
        // Logique pour afficher la liste des utilisateurs
        $userModel = new User_temp();
        $users = $userModel->getAllUsers(); // Méthode à créer dans User_temp
        include __DIR__.'/../Views/admin/users.php';
    }

    public function contents() {
        // Logique pour afficher la gestion des contenus
        include __DIR__.'/../Views/admin/contents.php';
    }

    public function orders() {
        // Logique pour afficher l'historique des achats
        include __DIR__.'/../Views/admin/orders.php';
    }

    public function certifications() {
        // Logique pour afficher les certifications
        include __DIR__.'/../Views/admin/certifications.php';
    }
}
