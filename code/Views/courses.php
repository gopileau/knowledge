<?php require_once __DIR__.'/../includes/header.php'; ?>

<div class="container">
    <h1>Thèmes et Cursus Disponibles</h1>
    
    <?php 
    if (!empty($groupedCourses)): 
        foreach ($groupedCourses as $themeName => $courses): 
    ?>
            <div class="theme-section">
                <h2 class="theme-title"><?= htmlspecialchars($themeName) ?></h2>
                
                <?php foreach ($courses as $course): ?>
                    <div class="course-card">
                        <h3><?= htmlspecialchars($course['course_title']) ?> - <?= htmlspecialchars($course['price']) ?> €</h3>
                        <p><?= htmlspecialchars($course['description']) ?></p>
                        <p>Durée: <?= htmlspecialchars($course['duration']) ?> heures</p>
                        
                        <form action="/courses/purchase" method="POST">
                            <input type="hidden" name="item_type" value="course">
                            <input type="hidden" name="item_id" value="<?= $course['course_id'] ?>">
                            <button type="submit" class="btn btn-primary">Ajouter au panier</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun thème ou cursus disponible pour le moment.</p>
    <?php endif; ?>
</div>

<?php require_once __DIR__.'/../includes/footer.php'; ?>
