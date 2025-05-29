<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Knowledge</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="auth-container">
        <h1>Connexion</h1>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="success-messages" style="color: green;">
                <ul>
                    <?php foreach ((array)$_SESSION['success'] as $msg): ?>
                        <li><?= htmlspecialchars($msg) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['errors'])): ?>
            <div class="error-messages">
                <ul>
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php unset($_SESSION['errors']); endif; ?>

        <form method="POST" class="auth-form">
            <!-- Champ caché pour le token CSRF -->
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" required value="<?= $_POST['email'] ?? '' ?>">
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Se connecter</button>
        </form>
        <p class="auth-link">Pas encore inscrit ? <a href="/register">Créez un compte</a></p>
    </div>
</body>
</html>
