<?php
require_once __DIR__.'/../../includes/auth.php';


if (!hasRole('admin')) {
    header('Location: /auth/login.php');
    exit;
}

$title = "Ajouter un nouveau cours";
include __DIR__.'/../layout/header.php';
?>

<div class="container">
    <h1><?= $title ?></h1>

    <form action="/admin/contents/create" method="POST">
        <div class="form-group">
            <label for="title">Titre du cours</label>
            <input type="text" id="title" name="title" required>
        </div>

        <div class="form-group">
            <label for="theme_id">Thème</label>
            <select id="theme_id" name="theme_id" required>
                <?php foreach ($themes as $theme): ?>
                    <option value="<?= htmlspecialchars($theme['id']) ?>"><?= htmlspecialchars($theme['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="price">Prix (€)</label>
            <input type="number" id="price" name="price" step="0.01" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4"></textarea>
        </div>

        <div class="form-group">
            <label for="duration">Durée (heures)</label>
            <input type="number" id="duration" name="duration" step="0.1" required>
        </div>

        <button type="submit" class="btn" style="color: white; background-color: #3498db; border-radius: 4px; padding: 0.5rem 1rem; text-decoration: none;">Ajouter</button>
        <a href="/admin/contents" class="btn btn-secondary" style="color: black; background-color: #e0e0e0; border-radius: 4px; padding: 0.5rem 1rem; text-decoration: none;">Annuler</a>
    </form>
</div>

<?php include __DIR__.'/../layout/footer.php'; ?>
