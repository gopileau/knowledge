<?php
namespace App\Models;

use PDO;
use Exception;

class User extends Database {
    private $id;
    private $email;
    private $password;
    private $role;
    private $is_activated;
    private $activation_token;

    public function __construct() {
        parent::__construct();
        $this->connection = $this->getConnection();
    }

    public function getUserCount() {
        $stmt = $this->connection->prepare("SELECT COUNT(*) as count FROM users");
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }

    public function register($email, $password) {
        $stmt = $this->connection->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            throw new Exception("Email already exists");
        }

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $this->activation_token = bin2hex(random_bytes(32));

        $stmt = $this->connection->prepare(
            "INSERT INTO users (email, password, activation_token, is_activated) 
            VALUES (:email, :password, :token, 1)"
        );

        if (!$stmt->execute([
            ':email' => $email,
            ':password' => $hashed_password,
            ':token' => $this->activation_token
        ])) {
            $error = $stmt->errorInfo();
            throw new Exception("Database error: " . $error[2]);
        }

        return $this->activation_token;
    }

    public function activateAccount($token) {
        $stmt = $this->connection->prepare(
            "UPDATE users SET is_activated = 1 
            WHERE activation_token = :token"
        );

        return $stmt->execute([':token' => $token]);
    }

    public function login($email, $password) {
        $stmt = $this->connection->prepare(
            "SELECT * FROM users WHERE email = :email"
        );
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new Exception("Email not found");
        }

        if (!$user['is_activated']) {
            throw new Exception("Account not activated");
        }

        if (!password_verify($password, $user['password'])) {
            throw new Exception("Invalid password");
        }

        return $user;
    }

    public function updateName($userId, $name) {
        $stmt = $this->connection->prepare("UPDATE users SET name = :name WHERE id = :id");
        return $stmt->execute([':name' => $name, ':id' => $userId]);
    }

    public function updateEmail($userId, $email) {
        $stmt = $this->connection->prepare("UPDATE users SET email = :email WHERE id = :id");
        return $stmt->execute([':email' => $email, ':id' => $userId]);
    }

    public function getUserById($id) {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Get recent activities for admin dashboard
    public function getRecentActivities($limit = 5) {
        // Since user_activities table does not exist, return empty array to avoid errors
        return [];
    }
}
