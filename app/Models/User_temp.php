<?php
namespace App\Models;

use App\Models\Database; // Correct namespace
use PDO;
use Exception;

class User_temp extends Database {
    private $id;
    private $email;
    private $password;
    private $role;
    private $is_activated;
    private $activation_token;
    protected $connection;

    public function __construct() {
        parent::__construct();
        $this->connection = $this->getConnection();
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
            VALUES (:email, :password, :token, 0)"
        );

        if ($stmt->execute([
            ':email' => $email,
            ':password' => $hashed_password,
            ':token' => $this->activation_token
        ])) {
            return $this->activation_token;
        } else {
            $error = $stmt->errorInfo();
            throw new Exception("Database error: " . $error[2]);
        }
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

        if (!password_verify($password, $user['password'])) {
            throw new Exception("Invalid password");
        }

        if ($user['is_activated'] != 1) {
            throw new Exception("Account not activated");
        }

        return $user;
    }

    public function activateAccount($token) {
        $stmt = $this->connection->prepare(
            "UPDATE users SET is_activated = 1, activation_token = NULL WHERE activation_token = :token"
        );
        $stmt->execute([':token' => $token]);
        return $stmt->rowCount() > 0;
    }
}
