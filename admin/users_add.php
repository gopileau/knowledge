<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Traitement du formulaire
    $email = sanitize($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = sanitize($_POST['role']);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$email, $password, $role]);
        
        $_SESSION['success'] = "Utilisateur créé avec succès";
        header('Location: users.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de la création: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajouter un utilisateur</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main class="container">
        <h1>Ajouter un utilisateur</h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Mot de passe:</label>
                <input type="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label>Rôle:</label>
                <select name="role" required>
                    <option value="user">Utilisateur</option>
                    <option value="admin">Administrateur</option>
                </select>
            </div>
            
            <button type="submit" class="btn">Créer l'utilisateur</button>
            <a href="users.php" class="btn cancel">Annuler</a>
        </form>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
