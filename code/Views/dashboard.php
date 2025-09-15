<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Knowledge Learning</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<?php require_once __DIR__.'/layout/header.php'; ?>

<div class="dashboard-container">
    <h1>Tableau de Bord</h1>

    <div class="stats">
        <div class="stat">
            <h2>Utilisateurs Inscrits</h2>
            <p id="userCount"><?= $userCount ?? 0 ?></p>
        </div>
        <div class="stat">
            <h2>Cours Disponibles</h2>
            <p id="courseCount"><?= $courseCount ?? 0 ?></p>
        </div>
        <div class="stat">
            <h2>Leçons Validées</h2>
            <p id="lessonCount"><?= $lessonCount ?? 0 ?></p>
        </div>
    </div>

    <div class="user-profile">
        <h2>Mon Profil</h2>
        <p><strong>Email:</strong> <?= isset($user['email']) ? htmlspecialchars($user['email']) : 'Non renseigné' ?></p>
        <p><strong>Nom:</strong> <?= isset($user['name']) ? htmlspecialchars($user['name']) : 'Non renseigné' ?></p>
        <a href="/profile/edit" class="edit-profile">Modifier mes informations</a>
    </div>

    <div class="my-courses">
        <h2>Mes cours</h2>
        <?php if (!empty($myCourses) && is_array($myCourses)): ?>
            <ul class="course-list" id="courseList">
                <?php foreach ($myCourses as $course): ?>
                    <li class="course-item" data-course-id="<?= $course['id'] ?>" draggable="true">
                        <strong><?= htmlspecialchars($course['title']) ?></strong>
                        <span>(<?= !empty($course['lessons']) && is_array($course['lessons']) ? count($course['lessons']) : 0 ?> leçon(s))</span>
                        <?php if (!empty($course['lessons']) && is_array($course['lessons'])): ?>
                            <ul class="lesson-list" data-course-id="<?= $course['id'] ?>" id="lessonList-<?= $course['id'] ?>">
                                <?php foreach ($course['lessons'] as $lesson): ?>
                                    <li class="lesson-item" data-lesson-id="<?= $lesson['id'] ?>" draggable="true">
                                        <a href="/lesson.php?id=<?= $lesson['id'] ?>">
                                            <?= htmlspecialchars($lesson['title']) ?>
                                        </a>
                                        <?= $lesson['is_validated'] ? '✅' : '⏳' ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <em>Aucune leçon achetée.</em>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Vous n’avez encore acheté aucun cours.</p>
        <?php endif; ?>
    </div>

    <div class="progression">
        <h2>Progression</h2>
        <?php if (!empty($lessonCount)): ?>
            <p>Vous avez validé <?= $lessonCount ?> leçon(s).</p>
        <?php else: ?>
            <p>Aucune progression enregistrée.</p>
        <?php endif; ?>
    </div>

    <div class="available-courses">
    <h2>Cours Disponibles</h2>
    <div class="course-list">
        <?php foreach($courses ?? [] as $course): ?>
            <div class="course-card">
                <h3><?= htmlspecialchars($course['title']) ?></h3>
                <p class="theme">Thème: <?= !empty($course['theme_name']) ? htmlspecialchars($course['theme_name']) : 'Non défini' ?></p>
                <p><?= isset($course['description']) ? htmlspecialchars($course['description']) : 'Pas de description disponible.' ?></p>
                <p class="price"><?= number_format($course['price'], 2) ?> €</p>

                <form action="/cart/add" method="post">
                    <input type="hidden" name="item_type" value="course">
                    <input type="hidden" name="item_id" value="<?= $course['id'] ?>">
                    <button type="submit" class="add-to-cart">Ajouter au panier</button>
                </form>

                <div class="lessons-list">
                    <?php
                    $courseModel = new App\Models\Course();
                    $customCourses = [
                        "Cursus d’initiation à la guitare" => [
                            ['title' => 'Découverte de l’instrument', 'price' => 26.00],
                            ['title' => 'Les accords et les gammes', 'price' => 26.00],
                        ],
                        "Cursus d’initiation au piano" => [
                            ['title' => 'Découverte de l’instrument', 'price' => 26.00],
                            ['title' => 'Les accords et les gammes', 'price' => 26.00],
                        ],
                        "Cursus d’initiation au développement web" => [
                            ['title' => 'Les langages Html et CSS', 'price' => 32.00],
                            ['title' => 'Dynamiser votre site avec Javascript', 'price' => 32.00],
                        ],
                        "Cursus d’initiation au jardinage" => [
                            ['title' => 'Les outils du jardinier', 'price' => 16.00],
                            ['title' => 'Jardiner avec la lune', 'price' => 16.00],
                        ],
                        "Cursus d’initiation à la cuisine" => [
                            ['title' => 'Les modes de cuisson', 'price' => 23.00],
                            ['title' => 'Les saveurs', 'price' => 23.00],
                        ],
                        "Cursus d’initiation à l’art du dressage culinaire" => [
                            ['title' => 'Mettre en œuvre le style dans l’assiette', 'price' => 26.00],
                            ['title' => 'Harmoniser un repas à quatre plats', 'price' => 26.00],
                        ]
                    ];

                    if (isset($customCourses[$course['title']])) {
                        foreach ($customCourses[$course['title']] as $lesson): ?>
                            <div class="lesson-item">
                                <h4><?= htmlspecialchars($lesson['title']) ?> - <?= number_format($lesson['price'], 2) ?> €</h4>
                                    <form action="/cart/add" method="post" style="display:inline-block; margin-left:10px;">
                                        <input type="hidden" name="item_type" value="lesson">
                                        <input type="hidden" name="item_id" value="<?= -1 * (array_search($lesson, $customCourses[$course['title']]) + 1) ?>">
                                        <input type="hidden" name="custom_course_title" value="<?= htmlspecialchars($course['title']) ?>">
                                        <button type="submit" class="btn btn-secondary">Ajouter cette leçon</button>
                                    </form>
                            </div>
                        <?php endforeach;
                    } else {
                        $lessons = $courseModel->getLessonsByCourse($course['id']);
                        foreach ($lessons as $lesson): ?>
                            <div class="lesson-item">
                                <h4><?= htmlspecialchars($lesson['title']) ?> - <?= htmlspecialchars($lesson['price']) ?> €</h4>
                                <form action="/cart/add" method="post" style="display:inline-block; margin-left:10px;">
                                    <input type="hidden" name="item_type" value="lesson">
                                    <input type="hidden" name="item_id" value="<?= $lesson['id'] ?>">
                                    <button type="submit" class="btn btn-secondary">Ajouter cette leçon</button>
                                </form>
                            </div>
                        <?php endforeach;
                    }
                    ?>
                </div>

                <?php if (isset($course['purchased']) && $course['purchased']): ?>
                    <p class="already-purchased">✅ Déjà acheté</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>


    <div class="purchases">
        <h2>Mes Achats</h2>
        <?php if (!empty($orders) && is_array($orders)): ?>
            <ul>
                <?php foreach($orders as $order): ?>
                    <li>Achat du <?= !empty($order['created_at']) ? date('d/m/Y', strtotime($order['created_at'])) : 'Date inconnue' ?> - <?= isset($order['total_price']) ? number_format($order['total_price'], 2) : '0.00' ?> €</li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Vous n'avez pas encore effectué d'achat.</p>
        <?php endif; ?>
    </div>

    <div class="recent-activity">
        <h2>Activité Récente</h2>
        <ul id="activityList">
            <?php if (!empty($recentActivities)): ?>
                <?php foreach($recentActivities as $activity): ?>
                    <li><?= htmlspecialchars($activity) ?></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Aucune activité récente.</li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="notifications">
        <h2>Notifications</h2>
        <?php if (!empty($notifications)): ?>
            <ul>
                <?php foreach($notifications as $notif): ?>
                    <li><?= htmlspecialchars($notif) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucune notification pour le moment.</p>
        <?php endif; ?>
    </div>

    <a href="/logout" class="logout-button">Se déconnecter</a>
</div>

<?php require_once __DIR__.'/layout/footer.php'; ?>
</body>
</html>
