<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
requireAdmin();

// Récupérer l'utilisateur à supprimer
$userId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$userId) {
    header('Location: users.php');
    exit;
}

// Vérifier que l'utilisateur ne se supprime pas lui-même
if ($userId === $_SESSION['user']['id']) {
    $_SESSION['error'] = "Vous ne pouvez pas supprimer votre propre compte";
    header('Location: users.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Confirmation de suppression
    if (isset($_POST['confirm'])) {
        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            
            $_SESSION['success'] = "Utilisateur supprimé avec succès";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Erreur lors de la suppression: " . $e->getMessage();
        }
    }
    header('Location: users.php');
    exit;
}

// Récupérer les infos de l'utilisateur pour affichage
$stmt = $pdo->prepare("SELECT email FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Supprimer un utilisateur</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main class="container">
        <h1>Supprimer un utilisateur</h1>
        
        <div class="confirmation-box">
            <p>Êtes-vous sûr de vouloir supprimer l'utilisateur <strong><?= htmlspecialchars($user['email']) ?></strong> ?</p>
            <p>Cette action est irréversible.</p>
            
            <form method="POST">
                <button type="submit" name="confirm" class="btn danger">Confirmer la suppression</button>
                <a href="users.php" class="btn">Annuler</a>
            </form>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
