<?php
require_once __DIR__.'/../config/database.php';

class AuthTest extends PHPUnit\Framework\TestCase {
    private $pdo;
    private $testEmail = 'test@example.com';
    private $testPassword = 'Test1234!';
    private $activationToken;

    protected function setUp(): void {
        global $pdo;
        $this->pdo = $pdo;
        // Nettoyer complètement la base de test avant chaque test
        $this->pdo->exec("TRUNCATE TABLE users");
    }

    public function testUserRegistration() {
        $stmt = $this->pdo->prepare("INSERT INTO users (email, password, activation_token) VALUES (?, ?, ?)");
        $this->activationToken = bin2hex(random_bytes(32));
        $passwordHash = password_hash($this->testPassword, PASSWORD_BCRYPT);
        $stmt->execute([$this->testEmail, $passwordHash, $this->activationToken]);
        
        $this->assertNotEmpty($this->activationToken);
    }

    public function testAccountActivation() {
        $this->testUserRegistration(); // Crée un utilisateur de test
        
        $stmt = $this->pdo->prepare("UPDATE users SET is_active = TRUE WHERE activation_token = ?");
        $result = $stmt->execute([$this->activationToken]);
        
        $this->assertTrue($result);
    }

    public function testUserLogin() {
        // Création directe d'un utilisateur pour ce test
        $stmt = $this->pdo->prepare("INSERT INTO users (email, password, is_active) VALUES (?, ?, TRUE)");
        $passwordHash = password_hash($this->testPassword, PASSWORD_BCRYPT);
        $stmt->execute([$this->testEmail, $passwordHash]);
        
        // Test connexion réussie
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ? AND is_active = TRUE");
        $stmt->execute([$this->testEmail]);
        $user = $stmt->fetch();
        
        $this->assertNotFalse($user);
        $this->assertEquals($this->testEmail, $user['email']);
        $this->assertTrue(password_verify($this->testPassword, $user['password']));

        // Test JWT généré
        $userModel = new \App\Models\User();
        $jwt = $userModel->generateJWT($user['id']);
        $this->assertNotEmpty($jwt);

        // Test mot de passe incorrect
        $this->assertFalse(password_verify('wrongpassword', $user['password']));

        // Test compte non activé
        $this->pdo->exec("UPDATE users SET is_active = FALSE WHERE email = '{$this->testEmail}'");
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ? AND is_active = TRUE");
        $stmt->execute([$this->testEmail]);
        $this->assertFalse($stmt->fetch());

        // Test vérification JWT
        $_COOKIE['jwt'] = $jwt;
        require_once __DIR__.'/../includes/auth.php';
        verifyJWT();
        $this->assertTrue(isset($_SESSION['user']));
    }

    protected function tearDown(): void {
        // Nettoyer après chaque test
        $this->pdo->exec("DELETE FROM users WHERE email = '{$this->testEmail}'");
    }
}
