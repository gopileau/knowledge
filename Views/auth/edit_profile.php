<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier mon profil - Knowledge Learning</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<?php require_once __DIR__.'/../layout/header.php'; ?>

<div class="edit-profile-container">
    <h1>Modifier mon profil</h1>

    <?php if (!empty($errors)): ?>
        <div class="error-messages">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="success-message">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <form action="/profile/edit" method="post">
        <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

        <label for="name">Nom:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>

        <button type="submit">Mettre à jour</button>
    </form>

    <a href="/dashboard">Retour au tableau de bord</a>
</div>

<?php require_once __DIR__.'/../layout/footer.php'; ?>
</body>
</html>
