<?php
namespace App\Models;

require_once __DIR__.'/Database.php';

class Course extends Database {
    private $id;
    private $theme_id;
    private $title;
    private $price;
    private $description;
    private $duration;

    public function getAllCourses() {
        $stmt = $this->connection->query("
            SELECT c.*, t.name AS theme_name 
            FROM courses c 
            LEFT JOIN themes t ON c.theme_id = t.id
        ");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function deleteCourse($id) {
        $stmt = $this->connection->prepare("DELETE FROM courses WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getCourseCount() {
        $stmt = $this->connection->prepare("SELECT COUNT(*) as count FROM courses");
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }

    public function getThemesWithCourses() {
        $stmt = $this->connection->query("
            SELECT 
                t.id as theme_id,
                t.name as theme_name,
                c.id as course_id,
                c.title as course_title,
                c.description,
                c.price,
                c.duration
            FROM themes t
            LEFT JOIN courses c ON c.theme_id = t.id
            ORDER BY t.name, c.title
        ");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getCoursesByTheme($themeId) {
        $stmt = $this->connection->prepare("
            SELECT * FROM courses 
            WHERE theme_id = ?
            ORDER BY title
        ");
        $stmt->execute([$themeId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getLessonsByCourse($courseId) {
        $stmt = $this->connection->prepare("
            SELECT * FROM lessons 
            WHERE course_id = ?
            ORDER BY id
        ");
        $stmt->execute([$courseId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getCourseDetails($courseId) {
        $stmt = $this->connection->prepare("
            SELECT c.*, t.name as theme_name 
            FROM courses c
            JOIN themes t ON c.theme_id = t.id
            WHERE c.id = ?
        ");
        $stmt->execute([$courseId]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getCoursesByUserId($userId) {
        $stmt = $this->connection->prepare("
            SELECT c.*, t.name AS theme_name
            FROM purchases p
            JOIN courses c ON p.course_id = c.id
            LEFT JOIN themes t ON c.theme_id = t.id
            WHERE p.user_id = ?
            ORDER BY c.title
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
