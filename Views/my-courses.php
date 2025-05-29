<?php require_once __DIR__.'/../includes/header.php'; ?>

<div class="container">
    <h1>Mes Cours Achetés</h1>
    
    <?php 
    if (!empty($purchasedCourses)): 
        $courseModel = new App\Models\Course();
    ?>
        <div class="course-list">
            <?php foreach ($purchasedCourses as $course): ?>
                <div class="course-card">
                    <h3><?= htmlspecialchars($course['title']) ?></h3>
                    <p><?= htmlspecialchars($course['description']) ?></p>
                    <p>Prix: <?= htmlspecialchars($course['price']) ?> €</p>
                    <p>Date d'achat: <?= date('d/m/Y', strtotime($course['purchase_date'])) ?></p>
                    
                    <div class="lessons-list">
                        <h4>Leçons incluses:</h4>
                        <?php 
                        $lessons = $courseModel->getLessonsByCourse($course['id']);
                        foreach ($lessons as $lesson): ?>
                            <div class="lesson-item">
                                <h5><?= htmlspecialchars($lesson['title']) ?></h5>
                                <p><?= htmlspecialchars($lesson['description']) ?></p>
                                <a href="/lesson.php?id=<?= $lesson['id'] ?>" class="btn">Accéder à la leçon</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Vous n'avez acheté aucun cours pour le moment.</p>
        <a href="/courses" class="btn">Voir les cours disponibles</a>
    <?php endif; ?>
</div>

<?php require_once __DIR__.'/../includes/footer.php'; ?>
