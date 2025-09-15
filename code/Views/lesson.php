<?php require_once __DIR__.'/../includes/header.php'; ?>

<div class="container">
    <h1><?= htmlspecialchars($lesson['title']) ?></h1>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
    
    <div class="video-container" style="margin: 20px 0;">
        <video width="640" height="360" controls>
            <source src="/videos/sample-video.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

    <p>Status: <?= $isValidated ? 'Validée' : 'Non validée' ?></p>

    <?php if (!$isValidated): ?>
        <form action="/lessons/validate" method="POST">
            <input type="hidden" name="lesson_id" value="<?= htmlspecialchars($lesson['id']) ?>">
            <button type="submit" class="btn btn-primary">Valider la leçon</button>
        </form>
    <?php else: ?>
        <p>Vous avez déjà validé cette leçon.</p>
    <?php endif; ?>
</div>

<?php require_once __DIR__.'/../includes/footer.php'; ?>
