<?php
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase {
    protected $cartController;

    protected function setUp(): void {
        // Simulate session start
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['cart'] = [];

        // Include CartController
        require_once __DIR__ . '/../app/Controllers/CartController.php';
        $this->cartController = new \App\Controllers\CartController();
    }

    public function testAddLessonToCart() {
        // Simulate POST data for a lesson
        $_POST['item_type'] = 'lesson';
        $_POST['item_id'] = '1'; // Assuming lesson with ID 1 exists

        // Call add method
        ob_start();
        $this->cartController->add();
        ob_end_clean();

        $this->assertCount(1, $_SESSION['cart']);
        $this->assertEquals('lesson', $_SESSION['cart'][0]['type']);
        $this->assertEquals('1', $_SESSION['cart'][0]['id']);
    }

    public function testAddDuplicateLesson() {
        // Add a lesson first
        $_SESSION['cart'][] = [
            'id' => '1',
            'name' => 'Test Lesson',
            'price' => 10.0,
            'quantity' => 1,
            'type' => 'lesson',
        ];

        // Simulate POST data for the same lesson
        $_POST['item_type'] = 'lesson';
        $_POST['item_id'] = '1';

        $this->expectOutputString("L'élément est déjà dans le panier.");

        // Call add method, expecting die() with message
        $this->cartController->add();
    }

    public function testAddCourseToCart() {
        // Simulate POST data for a course
        $_POST['item_type'] = 'course';
        $_POST['item_id'] = '1'; // Assuming course with ID 1 exists

        // Call add method
        ob_start();
        $this->cartController->add();
        ob_end_clean();

        $this->assertCount(1, $_SESSION['cart']);
        $this->assertEquals('course', $_SESSION['cart'][0]['type']);
        $this->assertEquals('1', $_SESSION['cart'][0]['id']);
    }

    public function testAddDuplicateCourse() {
        // Add a course first
        $_SESSION['cart'][] = [
            'id' => '1',
            'name' => 'Test Course',
            'price' => 50.0,
            'quantity' => 1,
            'type' => 'course',
        ];

        // Simulate POST data for the same course
        $_POST['item_type'] = 'course';
        $_POST['item_id'] = '1';

        $this->expectOutputString("L'élément est déjà dans le panier.");

        // Call add method, expecting die() with message
        $this->cartController->add();
    }
}
