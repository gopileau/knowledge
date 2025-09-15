<?php

namespace App\Controllers;

class HomeController {
    public function index() {
        header('Location: /dashboard');
        exit;
    }

    public function dashboard() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $user = $_SESSION['user'];

        $courseModel = new \App\Models\Course();
        $orderModel = new \App\Models\Order();
        $lessonModel = new \App\Models\Lesson();
        $userModel = new \App\Models\User();

        // Fetch user-specific data
        $myCourses = $courseModel->getCoursesByUserId($user['id']) ?? [];
        $orders = $orderModel->getOrdersByUserId($user['id']) ?? [];

        // Fetch counts
        $userCount = $userModel->getUserCount();
        $courseCount = $courseModel->getCourseCount();
        $lessonCount = $lessonModel->getLessonCountByUserId($user['id']);
        $userLessons = $lessonModel->getLessonsForUser($user['id']);
        $recentActivities = $userModel->getRecentActivities();
        $notifications = [];

        // Fetch all courses
        $courses = $courseModel->getAllCourses();

        $data = [
            'user' => $user,
            'message' => 'Bienvenue sur votre tableau de bord',
            'myCourses' => $myCourses,
            'orders' => $orders,
            'userCount' => $userCount,
            'courseCount' => $courseCount,
            'lessonCount' => $lessonCount,
            'userLessons' => $userLessons,
            'recentActivities' => $recentActivities,
            'notifications' => $notifications,
            'courses' => $courses
        ];

        $viewPath = realpath(__DIR__.'/../../Views/dashboard.php');

        if (file_exists($viewPath)) {
            extract($data);
            require_once $viewPath;
        } else {
            throw new \Exception("View file not found: $viewPath");
        }
    }

    public function publicDashboard() {
        $viewPath = realpath(__DIR__.'/../../Views/public/dashboard.php');
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            throw new \Exception("View file not found: $viewPath");
        }
    }
}
