<?php require_once __DIR__.'/../includes/header.php'; ?>

<div class="container">
    <h1>Mes Leçons</h1>
    
    <?php if (!empty($userLessons)): ?>
        <div class="lesson-list">
            <?php foreach ($userLessons as $lesson): ?>
                <div class="lesson-card">
                    <h3><?= htmlspecialchars($lesson['title']) ?></h3>
                    <p>Statut: <?= $lesson['validated'] ? 'Validée' : 'Non validée' ?></p>
                    <?php if (!$lesson['validated']): ?>
                        <form action="/lessons/validate" method="POST">
                            <input type="hidden" name="lesson_id" value="<?= $lesson['id'] ?>">
                            <button type="submit" class="btn btn-primary">Valider la leçon</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Aucune leçon suivie pour le moment.</p>
    <?php endif; ?>
</div>

<?php require_once __DIR__.'/../includes/footer.php'; ?>
