<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Knowledge') ?> - Knowledge</title>
    <link rel="icon" href="/images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <h1><a href="/"><img src="/images/logo K.jpeg" alt="Logo Knowledge" style="height:100px; vertical-align:middle; margin-right:10px;">Knowledge</a></h1>
            <nav class="main-nav">
                <a href="/cart"><i class="fas fa-shopping-cart"></i> Panier</a>
                <a href="/logout"><i class="fas fa-sign-out-alt"></i>Se déconnecter</a>
                <?php if (isset($_SESSION['user'])): ?>
                    <!-- Removed Déconnexion link as per request -->
                <?php else: ?>
                    <a href="/login"><i class="fas fa-sign-in-alt"></i> Connexion</a>
                    <a href="/register"><i class="fas fa-user-plus"></i> Inscription</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="container">
