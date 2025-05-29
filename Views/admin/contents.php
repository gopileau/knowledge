<?php
require_once __DIR__.'/../../includes/auth.php';
require_once __DIR__.'/../../includes/db.php';

if (!hasRole('admin')) {
    header('Location: /auth/login.php');
    exit;
}

$title = "Gestion des Contenus";
include __DIR__.'/../layout/header.php';
?>

<div class="container">
    <h1><?= $title ?></h1>
    
    <div class="admin-actions">
        <a href="/admin/contents/new">+ Nouveau contenu</a>
    </div>

    <h2>Cursus</h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Type</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($courses as $course): ?>
                <tr>
                    <td><?= htmlspecialchars($course['id']) ?></td>
                    <td><?= htmlspecialchars($course['title']) ?></td>
                    <td>Course</td>
                    <td>Active</td>
                    <td>
                        <a href="/admin/contents/edit/<?= $course['id'] ?>">Modifier</a> |
                        <a href="/admin/contents/delete/<?= $course['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce contenu ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Leçons</h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Type</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lessons as $lesson): ?>
                <tr>
                    <td><?= htmlspecialchars($lesson['id']) ?></td>
                    <td><?= htmlspecialchars($lesson['title']) ?></td>
                    <td>Lesson</td>
                    <td>Active</td>
            <td>
                <a href="/admin/contents/edit/<?= $lesson['id'] ?>">Modifier</a> |
                <a href="/admin/contents/delete/<?= $lesson['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette leçon ?');">Supprimer</a>
            </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div style="margin-top: 20px;">
        <a href="/admin/dashboard">Revenir a l'Accueil admin</a>
</div>

<?php include __DIR__.'/../layout/footer.php'; ?>
