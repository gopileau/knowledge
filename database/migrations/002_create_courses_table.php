<?php
require_once '../../config/config.php';

class CreateCoursesTable {
    private $connection;

    public function __construct() {
        $this->connection = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
            DB_USER,
            DB_PASS
        );
    }

    public function up() {
        // Création de la table themes si elle n'existe pas
        $this->connection->exec(
            "CREATE TABLE IF NOT EXISTS themes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL
            )"
        );

        $sql = "CREATE TABLE IF NOT EXISTS courses (
            id INT AUTO_INCREMENT PRIMARY KEY,
            theme_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (theme_id) REFERENCES themes(id)
        )";

        $this->connection->exec($sql);
        echo "Table courses créée avec succès\n";
    }

    public function down() {
        $sql = "DROP TABLE IF EXISTS courses";
        $this->connection->exec($sql);
        echo "Table courses supprimée avec succès\n";
    }
}

// Exécution de la migration
$migration = new CreateCoursesTable();
$migration->up();
