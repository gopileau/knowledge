<?php
namespace App\Models;

require_once __DIR__.'/Database.php';

class Lesson extends Database {
    protected $connection;

    public function __construct() {
        parent::__construct();
        $this->connection = $this->getConnection();
    }

    public function getLessonCount() {
        $stmt = $this->connection->prepare("SELECT COUNT(*) as count FROM lessons");
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }

    public function getLessonCountByUserId($userId) {
        $stmt = $this->connection->prepare("
            SELECT COUNT(*) as count
            FROM user_lessons ul
            JOIN lessons l ON ul.lesson_id = l.id
            WHERE ul.user_id = ?
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }

    public function getLessonsForUser($userId) {
        $stmt = $this->connection->prepare("
            SELECT l.*, CASE WHEN ul.lesson_id IS NOT NULL THEN 1 ELSE 0 END AS is_validated
            FROM lessons l
            JOIN courses c ON l.course_id = c.id
            JOIN purchases p ON p.course_id = c.id
            LEFT JOIN user_lessons ul ON ul.lesson_id = l.id AND ul.user_id = ?
            WHERE p.user_id = ?
            ORDER BY l.title
        ");
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getLessonById($lessonId) {
        $stmt = $this->connection->prepare("SELECT * FROM lessons WHERE id = ?");
        $stmt->execute([$lessonId]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
