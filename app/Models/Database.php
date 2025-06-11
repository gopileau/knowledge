<?php
namespace App\Models;

use PDO;
use PDOException;

class Database {
    protected $connection;

    public function __construct() {
        try {
            $this->connection = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
                DB_USER,
                DB_PASS
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}


