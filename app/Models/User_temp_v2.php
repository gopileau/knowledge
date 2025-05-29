<?php

namespace App\Models;

class User_temp_v2 {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllUsers() {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Autres méthodes existantes...
    public function register($email, $password) {
        // Implémentation existante
    }

    public function login($email, $password) {
        // Implémentation existante 
    }
}
