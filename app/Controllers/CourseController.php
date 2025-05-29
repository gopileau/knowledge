<?php
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/auth.php';

class CourseController {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function showUserCourses() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
        $userId = $_SESSION['user']['id'];
        $courseModel = new \App\Models\Course();

        // Fetch purchased courses with theme and description
        $purchasedCourses = $courseModel->getCoursesByUserId($userId);

        // For each course, fetch lessons and validation status
        foreach ($purchasedCourses as &$course) {
            $lessons = $courseModel->getLessonsByCourse($course['id']);
            foreach ($lessons as &$lesson) {
                $stmt = $this->pdo->prepare('SELECT validated FROM user_lessons WHERE user_id = ? AND lesson_id = ?');
                $stmt->execute([$userId, $lesson['id']]);
                $lesson['validated'] = (bool)$stmt->fetchColumn();
            }
            $course['lessons'] = $lessons;
        }

        include 'Views/my-courses.php';
    }

    public function showUserLessons() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
        $userId = $_SESSION['user']['id'];
        $stmt = $this->pdo->prepare('
            SELECT l.*, ul.validated
            FROM lessons l
            JOIN user_lessons ul ON l.id = ul.lesson_id
            WHERE ul.user_id = ?
        ');
        $stmt->execute([$userId]);
        $userLessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'Views/my-lessons.php';
    }

    public function showCourses() {
        session_start();
        $courseModel = new \App\Models\Course();
        $themesWithCourses = $courseModel->getThemesWithCourses();

        // Filter and group courses by specific themes (blocks)
        $blocks = ['Musique', 'Informatique', 'Jardinage', 'Cuisine'];
        $groupedCourses = [];

        foreach ($themesWithCourses as $item) {
            if (in_array($item['theme_name'], $blocks)) {
                if (!isset($groupedCourses[$item['theme_name']])) {
                    $groupedCourses[$item['theme_name']] = [];
                }
                if ($item['course_id']) {
                    $groupedCourses[$item['theme_name']][] = $item;
                }
            }
        }

        // Pass grouped courses to the view
        include 'Views/courses.php';
    }

    public function getDashboardCourses() {
        session_start();
        
        // Get all available courses with theme names
        $stmt = $this->pdo->query('
            SELECT c.*, t.name as theme_name 
            FROM courses c
            JOIN themes t ON c.theme_id = t.id
        ');
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Add purchase status for authenticated users
        if (isset($_SESSION['user'])) {
            $userId = $_SESSION['user']['id'];
            foreach ($courses as &$course) {
                $stmt = $this->pdo->prepare('SELECT 1 FROM purchases WHERE user_id = ? AND course_id = ?');
                $stmt->execute([$userId, $course['id']]);
                $course['purchased'] = (bool)$stmt->fetchColumn();
            }
        }
        
        return $courses;
    }

    public function showCourse($courseId) {
        // Get course details with theme
        $stmt = $this->pdo->prepare('
            SELECT c.*, t.name as theme_name
            FROM courses c
            LEFT JOIN themes t ON c.theme_id = t.id
            WHERE c.id = ?
        ');
        $stmt->execute([$courseId]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);

        // Get lessons for this course
        $stmt = $this->pdo->prepare('SELECT * FROM lessons WHERE course_id = ?');
        $stmt->execute([$courseId]);
        $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'Views/cursus.php';
    }

    public function showDashboard() {
        session_start();
        $courseModel = new Course();
        $courses = $courseModel->getAllCourses();

        // Get dashboard stats
        $userCount = $this->pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
        $lessonCount = $this->pdo->query('SELECT COUNT(*) FROM lessons')->fetchColumn();
        $courseCount = count($courses);

        // Add purchase status if user is logged in
        if (isset($_SESSION['user'])) {
            $userId = $_SESSION['user']['id'];
            foreach ($courses as &$course) {
                $stmt = $this->pdo->prepare('SELECT 1 FROM purchases WHERE user_id = ? AND course_id = ?');
                $stmt->execute([$userId, $course['id']]);
                $course['purchased'] = (bool)$stmt->fetchColumn();
            }
        }

        include 'Views/dashboard.php';
    }

    public function showLesson($lessonId) {
        session_start();
        $stmt = $this->pdo->prepare('SELECT * FROM lessons WHERE id = ?');
        $stmt->execute([$lessonId]);
        $lesson = $stmt->fetch(PDO::FETCH_ASSOC);

        $isValidated = false;
        if (isset($_SESSION['user'])) {
            $stmt = $this->pdo->prepare('SELECT 1 FROM user_lessons WHERE user_id = ? AND lesson_id = ? AND validated = 1');
            $stmt->execute([$_SESSION['user']['id'], $lessonId]);
            $isValidated = (bool)$stmt->fetchColumn();
        }

        include 'Views/lesson.php';
    }

    public function validateLesson($lessonId) {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $stmt = $this->pdo->prepare('INSERT INTO user_lessons (user_id, lesson_id, validated) VALUES (?, ?, 1) 
                                   ON DUPLICATE KEY UPDATE validated = 1');
        $stmt->execute([$_SESSION['user']['id'], $lessonId]);
        
        header('Location: /lesson.php?id='.$lessonId);
        exit;
    }

    public function showCertifications() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        
        // Get courses where all lessons are validated
        $stmt = $this->pdo->prepare('
            SELECT c.* FROM courses c
            WHERE NOT EXISTS (
                SELECT l.id FROM lessons l
                WHERE l.course_id = c.id
                AND NOT EXISTS (
                    SELECT 1 FROM user_lessons ul
                    WHERE ul.lesson_id = l.id
                    AND ul.user_id = ?
                    AND ul.validated = 1
                )
            )
        ');
        $stmt->execute([$userId]);
        $certifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'Views/certifications.php';
    }

    public function purchase() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $itemType = $_POST['item_type'] ?? null;
        $itemId = $_POST['item_id'] ?? null;

        if (!$itemType || !$itemId) {
            http_response_code(400);
            echo "Invalid purchase request.";
            exit;
        }

        if ($itemType === 'course') {
            $stmt = $this->pdo->prepare('INSERT INTO purchases (user_id, course_id) VALUES (?, ?)');
            $stmt->execute([$userId, $itemId]);
        } elseif ($itemType === 'lesson') {
            // Insert lesson purchase as unvalidated in user_lessons table
            $stmt = $this->pdo->prepare('INSERT INTO user_lessons (user_id, lesson_id, validated) VALUES (?, ?, 0) ON DUPLICATE KEY UPDATE validated = 0');
            $stmt->execute([$userId, $itemId]);
        } else {
            http_response_code(400);
            echo "Invalid item type.";
            exit;
        }

        header('Location: /purchase-success');
        exit;
    }
}
