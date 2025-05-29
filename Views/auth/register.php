<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - Knowledge</title>
</head>
<body>
    <h1>Inscription</h1>

    <!-- Affichage des erreurs si elles existent -->
    <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
        <div style="color: red;">
            <ul>
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <form method="POST" action="/register"> <!-- Assure-toi que l'action correspond à la route -->
        <input type="text" name="username" placeholder="Nom d'utilisateur" required value="<?php echo $_POST['username'] ?? ''; ?>">
        <input type="email" name="email" placeholder="Email" required value="<?php echo $_POST['email'] ?? ''; ?>">
        <input type="password" name="password" placeholder="Mot de passe" required>
        <input type="password" name="password_confirm" placeholder="Confirmer le mot de passe" required>
        
        <!-- Champ caché pour le token CSRF -->
        <input type="hidden" name="_token" value="<?= $_SESSION['csrf_token'] ?>">

        <button type="submit">S'inscrire</button>
    </form>

    <p>Déjà inscrit ? <a href="/login">Connectez-vous</a></p>
</body>
</html>
