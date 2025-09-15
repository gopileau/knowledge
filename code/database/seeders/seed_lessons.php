<?php
require_once __DIR__.'/../../config/config.php';

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
        DB_USER,
        DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $lessons = [
        // Cursus d’initiation à la guitare (id 1 and 2)
        ['course_id' => 1, 'title' => 'Découverte de l’instrument', 'description' => 'Introduction à la guitare', 'price' => 26, 'position' => 1],
        ['course_id' => 1, 'title' => 'Les accords et les gammes', 'description' => 'Apprentissage des accords et gammes', 'price' => 26, 'position' => 2],
        ['course_id' => 2, 'title' => 'Découverte de l’instrument', 'description' => 'Introduction au piano', 'price' => 26, 'position' => 1],
        ['course_id' => 2, 'title' => 'Les accords et les gammes', 'description' => 'Apprentissage des accords et gammes', 'price' => 26, 'position' => 2],

        // Cursus d’initiation au développement web (id 4)
        ['course_id' => 4, 'title' => 'Les langages Html et CSS', 'description' => 'Introduction aux langages web', 'price' => 32, 'position' => 1],
        ['course_id' => 4, 'title' => 'Dynamiser votre site avec Javascript', 'description' => 'Apprendre Javascript pour dynamiser un site', 'price' => 32, 'position' => 2],

        // Cursus d’initiation au jardinage (id 5)
        ['course_id' => 5, 'title' => 'Les outils du jardinier', 'description' => 'Présentation des outils essentiels', 'price' => 16, 'position' => 1],
        ['course_id' => 5, 'title' => 'Jardiner avec la lune', 'description' => 'Techniques de jardinage selon les phases lunaires', 'price' => 16, 'position' => 2],

        // Cursus d’initiation à la cuisine (id 6)
        ['course_id' => 6, 'title' => 'Les modes de cuisson', 'description' => 'Découverte des différentes méthodes de cuisson', 'price' => 23, 'position' => 1],
        ['course_id' => 6, 'title' => 'Les saveurs', 'description' => 'Apprendre à associer les saveurs', 'price' => 23, 'position' => 2],

        // Cursus d’initiation à l’art du dressage culinaire (id 7)
        ['course_id' => 7, 'title' => 'Mettre en œuvre le style dans l’assiette', 'description' => 'Techniques de dressage', 'price' => 26, 'position' => 1],
        ['course_id' => 7, 'title' => 'Harmoniser un repas à quatre plats', 'description' => 'Conseils pour un repas harmonieux', 'price' => 26, 'position' => 2],
    ];

    $stmt = $pdo->prepare("INSERT INTO lessons (course_id, title, description, price, position) VALUES (?, ?, ?, ?, ?)");

    foreach ($lessons as $lesson) {
        $stmt->execute([
            $lesson['course_id'],
            $lesson['title'],
            $lesson['description'],
            $lesson['price'],
            $lesson['position']
        ]);
    }

    echo "Lessons seeded successfully.\n";

} catch (PDOException $e) {
    echo "Error seeding lessons: " . $e->getMessage() . "\n";
}
?>
