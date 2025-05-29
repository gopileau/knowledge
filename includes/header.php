<?php require_once __DIR__ . '/auth.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Knowledge Learning') ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/style_admin.css">
    <script src="/js/main.js" defer></script>
</head>
<body>
    <header>
        <nav>
            <a href="/">Accueil</a>
            <?php if (isLoggedIn()): ?>
                <?php if (hasRole('admin')): ?>
                    <a href="/admin/dashboard.php">Administration</a>
                <?php endif; ?>
                <a href="/auth/logout.php">Déconnexion</a>
            <?php else: ?>
                <a href="/auth/login.php">Connexion</a>
                <a href="/auth/register.php">Inscription</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
