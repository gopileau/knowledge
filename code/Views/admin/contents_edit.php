<?php
require_once __DIR__.'/../../includes/auth.php';

if (!hasRole('admin')) {
    header('Location: /auth/login.php');
    exit;
}

$title = "Modifier le cours";
include __DIR__.'/../layout/header.php';
?>

<div class="container">
    <h1><?= $title ?></h1>

    <form action="/admin/contents/update/<?= htmlspecialchars($course['id']) ?>" method="POST">
        <div class="form-group">
            <label for="title">Titre du cours</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($course['title']) ?>" required>
        </div>

        <div class="form-group">
            <label for="theme_id">Thème</label>
            <select id="<create_file>
<path>Views/admin/contents_edit.php</path>
<content>
<?php
require_once __DIR__.'/../../includes/auth.php';

if (!hasRole('admin')) {
    header('Location: /auth/login.php');
    exit;
}

$title = "Modifier le cours";
include __DIR__.'/../layout/header.php';
?>

<div class="container">
    <h1><?= $title ?></h1>

    <form action="/admin/contents/update/<?= htmlspecialchars($course['id']) ?>" method="POST">
        <div class="form-group">
            <label for="title">Titre du cours</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($course['title']) ?>" required>
        </div>

        <div class="form-group">
            <label for="theme_id">Thème</label>
            <select id="theme_id" name="theme_id" required>
                <?php foreach ($themes as $theme): ?>
                    <option value="<?= htmlspecialchars($theme['id']) ?>" <?= $theme['id'] == ($course['theme_id'] ?? '') ? 'selected' : '' ?>>
                        <?= htmlspecialchars($theme['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="price">Prix (€)</label>
            <input type="number" id="price" name="price" step="0.01" value="<?= htmlspecialchars($course['price'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4"><?= htmlspecialchars($course['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="duration">Durée (heures)</label>
            <input type="number" id="duration" name="duration" step="0.1" value="<?= htmlspecialchars($course['duration'] ?? '') ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="/admin/contents" class="btn btn-secondary" style="color: black;">Annuler</a>
    </form>
</div>

<?php include __DIR__.'/../layout/footer.php'; ?>
