<?php
session_start();
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Check that the user is admin
if (!isAdmin()) {
    header('Location: ../auth/login.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Knowledge Learning</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h1>Tableau de bord - Knowledge Learning</h1>
    <nav>
        <a href="users.php">Utilisateurs</a> |
        <a href="contents.php">Contenus</a> |
        <a href="orders.php">Achats</a> |
        <a href="certifications.php">Certifications</a>
    </nav>

    <section>
        <p>Bienvenue, administrateur ! Que souhaitez-vous gérer ?</p>
    </section>
</body>
</html>
