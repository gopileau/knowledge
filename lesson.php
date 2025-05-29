<?php
require_once __DIR__.'/vendor/autoload.php';

use App\Controllers\CourseController;

session_start();

if (!isset($_GET['id'])) {
    echo "Aucun ID de leçon fourni.";
    exit;
}

$lessonId = (int) $_GET['id'];
$controller = new CourseController();

// Fetch lesson details
$pdo = new PDO('mysql:host=localhost;dbname=your_db_name;charset=utf8', 'username', 'password'); // Adjust connection as needed
$stmt = $pdo->prepare('SELECT * FROM lessons WHERE id = ?');
$stmt->execute([$lessonId]);
$lesson = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$lesson) {
    echo "Leçon introuvable.";
    exit;
}

// Check if lesson is validated by user
$isValidated = false;
if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user']['id'];
    $stmt = $pdo->prepare('SELECT validated FROM user_lessons WHERE user_id = ? AND lesson_id = ?');
    $stmt->execute([$userId, $lessonId]);
    $isValidated = (bool)$stmt->fetchColumn();
}

// Handle validation form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['validate'])) {
    if (!isset($_SESSION['user'])) {
        header('Location: /login');
        exit;
    }
    $stmt = $pdo->prepare('INSERT INTO user_lessons (user_id, lesson_id, validated) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE validated = 1');
    $stmt->execute([$userId, $lessonId]);
    $isValidated = true;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($lesson['title']) ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <h1><?= htmlspecialchars($lesson['title']) ?></h1>
    <p><?= nl2br(htmlspecialchars($lesson['content'])) ?></p>

    <!-- Placeholder for video -->
    <?php if (!empty($lesson['video_url'])): ?>
        <video width="640" height="360" controls>
            <source src="<?= htmlspecialchars($lesson['video_url']) ?>" type="video/mp4">
            Votre navigateur ne supporte pas la lecture vidéo.
        </video>
    <?php endif; ?>

    <?php if ($isValidated): ?>
        <p>Leçon validée ✅</p>
    <?php else: ?>
        <form method="POST">
            <button type="submit" name="validate">Valider la leçon</button>
        </form>
    <?php endif; ?>
</body>
</html>
