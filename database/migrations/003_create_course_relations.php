<?php
require_once __DIR__.'/../../config/config.php';

class CreateCourseRelations {
    private $connection;

    public function __construct() {
        $this->connection = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
            DB_USER,
            DB_PASS
        );
    }

    public function up() {
        // Create purchases table
        $this->connection->exec(
            "CREATE TABLE IF NOT EXISTS purchases (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                course_id INT NOT NULL,
                purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id),
                FOREIGN KEY (course_id) REFERENCES courses(id)
            )"
        );

        // Create user_lessons table
        $this->connection->exec(
            "CREATE TABLE IF NOT EXISTS user_lessons (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                lesson_id INT NOT NULL,
                validated BOOLEAN DEFAULT FALSE,
                validation_date TIMESTAMP NULL,
                FOREIGN KEY (user_id) REFERENCES users(id)
            )"
        );

        // Create certificates table
        $this->connection->exec(
            "CREATE TABLE IF NOT EXISTS certificates (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                issue_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                certificate_data TEXT,
                FOREIGN KEY (user_id) REFERENCES users(id)
            )"
        );

        echo "Course relation tables created successfully\n";
    }

    public function down() {
        $tables = ['purchases', 'user_lessons', 'certificates'];
        foreach ($tables as $table) {
            $this->connection->exec("DROP TABLE IF EXISTS $table");
        }
        echo "Course relation tables dropped successfully\n";
    }
}

// Execute migration
$migration = new CreateCourseRelations();
$migration->up();
