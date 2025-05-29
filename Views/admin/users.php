<?php
require_once __DIR__.'/../../includes/auth.php';

// Vérifier les permissions
if (!hasRole('admin')) {
    header('Location: /auth/login.php');
    exit;
}

// Titre de la page
$title = "Gestion des Utilisateurs";
include __DIR__.'/../layout/header.php';

?>

<div class="container">
    <h1><?= $title ?></h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td><?= htmlspecialchars($user['status'] ?? '') ?></td>
                    <td>
                        <a href="edit.php?id=<?= $user['id'] ?>">Modifier</a>
                        <a href="delete.php?id=<?= $user['id'] ?>">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div style="margin-top: 20px;">
        <a href="/admin/dashboard">Revenir a l'Accueil admin</a>
    </div>

<?php 
include __DIR__.'/../layout/footer.php';
?>
