<?php
require_once __DIR__.'/../../config/config.php';

class CreateUsersTable {
    private $connection;

    public function __construct() {
        $this->connection = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
            DB_USER,
            DB_PASS
        );
    }

    public function up() {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'client') DEFAULT 'client',
            is_active BOOLEAN DEFAULT FALSE,
            activation_token VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

        $this->connection->exec($sql);
        echo "Table users créée avec succès\n";
    }

    public function down() {
        $sql = "DROP TABLE IF EXISTS users";
        $this->connection->exec($sql);
        echo "Table users supprimée avec succès\n";
    }
}

// Exécution de la migration
$migration = new CreateUsersTable();
$migration->up();
