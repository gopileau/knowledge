<?php

namespace App\Controllers;

use App\Models\User_temp_v2 as UserModel;
use App\Models\Course;

require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';

class AdminController {

    private function ensureAdmin() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /auth/login.php');
            exit;
        }
    }

    public function dashboard() {
        $this->ensureAdmin();

        $viewPath = __DIR__ . '/../../Views/admin/dashboard.php';
        if (!is_file($viewPath)) {
            throw new \Exception("View file not found: $viewPath");
        }

        include $viewPath;
    }

    public function editLesson($id) {
        $this->ensureAdmin();

        $pdo = db();
        $stmt = $pdo->prepare("SELECT * FROM lessons WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $lesson = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$lesson) {
            $_SESSION['error'] = "Leçon non trouvée.";
            header('Location: /admin/contents');
            exit;
        }

        include __DIR__ . '/../../Views/admin/lessons_edit.php';
    }

    public function updateLesson($id) {
        $this->ensureAdmin();

        $title = $_POST['title'] ?? '';
        $course_id = $_POST['course_id'] ?? null;
        $price = $_POST['price'] ?? 0;
        $description = $_POST['description'] ?? '';
        $duration = $_POST['duration'] ?? 0;

        $pdo = db();
        $stmt = $pdo->prepare("UPDATE lessons SET title = :title, course_id = :course_id, price = :price, description = :description, duration = :duration WHERE id = :id");
        $updated = $stmt->execute([
            'title' => $title,
            'course_id' => $course_id,
            'price' => $price,
            'description' => $description,
            'duration' => $duration,
            'id' => $id
        ]);

        $_SESSION[$updated ? 'success' : 'error'] = $updated
            ? "La leçon a été mise à jour avec succès."
            : "Erreur lors de la mise à jour de la leçon.";

        header('Location: /admin/contents');
        exit;
    }

    public function users() {
        $userModel = new UserModel(db());
        $users = $userModel->getAllUsers(); // Assure-toi que cette méthode existe
        include __DIR__ . '/../../Views/admin/users.php';
    }

    public function contents() {
        $courseModel = new Course();
        $courses = $courseModel->getAllCourses();

        $pdo = db();
        $stmt = $pdo->prepare("
            SELECT lessons.*, courses.title AS course_title
            FROM lessons
            LEFT JOIN courses ON lessons.course_id = courses.id
            ORDER BY courses.title, lessons.title
        ");
        $stmt->execute();
        $lessons = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        include __DIR__ . '/../../Views/admin/contents.php';
    }

    public function newCourse() {
        $this->ensureAdmin();
        $themes = []; // À adapter si tu ajoutes un modèle Theme
        include __DIR__ . '/../../Views/admin/contents_new.php';
    }

    public function createCourse() {
        $this->ensureAdmin();

        $title = $_POST['title'] ?? '';
        $theme_id = $_POST['theme_id'] ?? '';
        $price = $_POST['price'] ?? 0;
        $description = $_POST['description'] ?? '';
        $duration = $_POST['duration'] ?? 0;

        $courseModel = new Course();
        $courseModel->createCourse($title, $theme_id, $price, $description, $duration);

        header('Location: /admin/contents');
        exit;
    }

    public function delete($id) {
        $courseModel = new Course();
        $deleted = $courseModel->deleteCourse($id);

        $_SESSION[$deleted ? 'success' : 'error'] = $deleted
            ? "Le contenu a été supprimé avec succès."
            : "Erreur lors de la suppression du contenu.";

        header('Location: /admin/contents');
        exit;
    }

    public function deleteLesson($id) {
        $this->ensureAdmin();

        $pdo = db();
        $stmt = $pdo->prepare("DELETE FROM lessons WHERE id = :id");
        $deleted = $stmt->execute(['id' => $id]);

        $_SESSION[$deleted ? 'success' : 'error'] = $deleted
            ? "La leçon a été supprimée avec succès."
            : "Erreur lors de la suppression de la leçon.";

        header('Location: /admin/contents');
        exit;
    }

    public function editCourse($id) {
        $this->ensureAdmin();

        $courseModel = new Course();
        $course = $courseModel->getCourseDetails($id);

        if (!$course) {
            $_SESSION['error'] = "Cours non trouvé.";
            header('Location: /admin/contents');
            exit;
        }

        $themes = []; // À ajuster si les thèmes sont utilisés
        include __DIR__ . '/../../Views/admin/contents_edit.php';
    }

    public function updateCourse($id) {
        $this->ensureAdmin();

        $title = $_POST['title'] ?? '';
        $theme_id = $_POST['theme_id'] ?? '';
        $price = $_POST['price'] ?? 0;
        $description = $_POST['description'] ?? '';
        $duration = $_POST['duration'] ?? 0;

        $courseModel = new Course();
        $updated = $courseModel->updateCourse($id, $title, $theme_id, $price, $description, $duration);

        $_SESSION[$updated ? 'success' : 'error'] = $updated
            ? "Le cours a été mis à jour avec succès."
            : "Erreur lors de la mise à jour du cours.";

        header('Location: /admin/contents');
        exit;
    }

    public function orders() {
        include __DIR__ . '/../../Views/admin/orders.php';
    }

    public function certifications() {
        include __DIR__ . '/../../Views/admin/certifications.php';
    }
}