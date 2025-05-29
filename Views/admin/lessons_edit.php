<?php
require_once __DIR__.'/../../includes/auth.php';

if (!hasRole('admin')) {
    header('Location: /auth/login.php');
    exit;
}

$title = "Modifier la leçon";
include __DIR__.'/../layout/header.php';
?>

<div class="container">
    <h1><?= $title ?></h1>

    <form action="/admin/contents/update/<?= htmlspecialchars($lesson['id']) ?>" method="POST">
        <div class="form-group">
            <label for="title">Titre de la leçon</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($lesson['title']) ?>" required>
        </div>

        <div class="form-group">
            <label for="course_id">Cours associé</label>
            <select id="course_id" name="course_id" required>
                <?php
                // Fetch courses for selection
                $pdo = db();
                $stmt = $pdo->prepare("SELECT id, title FROM courses ORDER BY title");
                $stmt->execute();
                $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($courses as $courseOption): ?>
                    <option value="<?= htmlspecialchars($courseOption['id']) ?>" <?= $courseOption['id'] == $lesson['course_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($courseOption['title']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="price">Prix (€)</label>
            <input type="number" id="price" name="price" step="0.01" value="<?= htmlspecialchars($lesson['price'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4"><?= htmlspecialchars($lesson['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="duration">Durée (heures)</label>
            <input type="number" id="duration" name="duration" step="0.1" value="<?= htmlspecialchars($lesson['duration'] ?? '') ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="/admin/contents" class="btn btn-secondary" style="color: black;">Annuler</a>
    </form>
</div>

<?php include __DIR__.'/../layout/footer.php'; ?>
