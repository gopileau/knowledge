<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireAdmin();

// Récupérer les utilisateurs
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestion Utilisateurs</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main class="container">
        <h1>Gestion des Utilisateurs</h1>
        
        <div class="admin-actions">
            <a href="users_add.php" class="btn">+ Nouvel utilisateur</a>
        </div>

        <table class="admin-table">
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
                    <td><?= $user['is_active'] ? 'Actif' : 'Inactif' ?></td>
                    <td class="actions">
                        <a href="users_edit.php?id=<?= $user['id'] ?>" class="btn-sm">Modifier</a>
                        <a href="users_delete.php?id=<?= $user['id'] ?>" class="btn-sm danger">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
