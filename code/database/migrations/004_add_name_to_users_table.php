<?php
require_once __DIR__.'/../../config/config.php';

class AddNameToUsersTable {
    private $connection;

    public function __construct() {
        $this->connection = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
            DB_USER,
            DB_PASS
        );
    }

    public function up() {
        $sql = "ALTER TABLE users ADD COLUMN name VARCHAR(255) NULL AFTER email";
        $this->connection->exec($sql);
        echo "Column 'name' added to users table successfully\n";
    }

    public function down() {
        $sql = "ALTER TABLE users DROP COLUMN name";
        $this->connection->exec($sql);
        echo "Column 'name' removed from users table successfully\n";
    }
}

// Execute the migration
$migration = new AddNameToUsersTable();
$migration->up();
