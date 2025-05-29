<?php
require_once __DIR__.'/../../config/config.php';

class AddOrderColumnsToPurchasesAndUserLessons {
    private $connection;

    public function __construct() {
        $this->connection = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
            DB_USER,
            DB_PASS
        );
    }

    public function up() {
        // Add order column to purchases table
        $this->connection->exec("
            ALTER TABLE purchases
            ADD COLUMN `order` INT NOT NULL DEFAULT 0
        ");

        // Add order column to user_lessons table
        $this->connection->exec("
            ALTER TABLE user_lessons
            ADD COLUMN `order` INT NOT NULL DEFAULT 0
        ");

        echo "Order columns added to purchases and user_lessons tables successfully\n";
    }

    public function down() {
        // Remove order column from purchases table
        $this->connection->exec("
            ALTER TABLE purchases
            DROP COLUMN `order`
        ");

        // Remove order column from user_lessons table
        $this->connection->exec("
            ALTER TABLE user_lessons
            DROP COLUMN `order`
        ");

        echo "Order columns removed from purchases and user_lessons tables successfully\n";
    }
}

// Execute migration
$migration = new AddOrderColumnsToPurchasesAndUserLessons();
$migration->up();
