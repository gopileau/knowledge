<?php
require_once '../database/migrations/001_create_users_table.php';
require_once '../database/migrations/002_create_courses_table.php';

class MigrationsTest extends PHPUnit\Framework\TestCase {
    public function testUsersTableCreation() {
        $migration = new CreateUsersTable();
        $migration->up();
        
        // Vérifier que la table existe
        $result = $migration->getConnection()->query("SHOW TABLES LIKE 'users'");
        $this->assertEquals(1, $result->rowCount());
    }

    public function testCoursesTableCreation() {
        $migration = new CreateCoursesTable();
        $migration->up();
        
        // Vérifier que la table existe
        $result = $migration->getConnection()->query("SHOW TABLES LIKE 'courses'");
        $this->assertEquals(1, $result->rowCount());
        
        // Vérifier que la table themes existe (créée par la migration courses)
        $result = $migration->getConnection()->query("SHOW TABLES LIKE 'themes'");
        $this->assertEquals(1, $result->rowCount());
    }

    protected function tearDown(): void {
        // Nettoyer après les tests
        $usersMigration = new CreateUsersTable();
        $usersMigration->down();
        
        $coursesMigration = new CreateCoursesTable();
        $coursesMigration->down();
    }
}
