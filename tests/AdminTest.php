<?php
use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase {
    protected $adminController;

    protected function setUp(): void {
        // Simulate session start and admin login
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user'] = ['role' => 'admin'];

        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $this->adminController = new \App\Controllers\AdminController();
    }

    public function testUsersList() {
        ob_start();
        $this->adminController->users();
        $output = ob_get_clean();
        $this->assertStringContainsString('Liste des utilisateurs', $output);
    }

    public function testContentsList() {
        ob_start();
        $this->adminController->contents();
        $output = ob_get_clean();
        $this->assertStringContainsString('Gestion des contenus', $output);
    }

    public function testOrdersList() {
        ob_start();
        $this->adminController->orders();
        $output = ob_get_clean();
        $this->assertStringContainsString('Historique des achats', $output);
    }

    public function testCertificationsList() {
        ob_start();
        $this->adminController->certifications();
        $output = ob_get_clean();
        $this->assertStringContainsString('Certifications', $output);
    }

    // Additional tests for create, edit, update, delete courses can be added here
}
