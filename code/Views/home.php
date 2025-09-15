<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Knowledge</title>
    <!-- Icône principale (ancien format) -->
    <link rel="icon" href="/images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
    <!-- Manifest pour PWA -->
    <link rel="manifest" href="/site.webmanifest">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="home-container">
        <h1>Bienvenue sur <span style="color: #007bff;">Knowledge</span></h1>

        <?php if ($isLoggedIn): ?>
            <a href="/dashboard" class="btn">Accéder au tableau de bord</a>
            <a href="/logout" class="btn logout">Déconnexion</a>
        <?php else: ?>
            <p>Accédez à votre espace d'apprentissage :</p>
            <a href="/login" class="btn">Se connecter</a>
            <a href="/register" class="btn">Créer un compte</a>
        <?php endif; ?>
    </div>
</body>
</html>
