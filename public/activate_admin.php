<?php
require_once __DIR__.'/../includes/db.php';

$email = 'lauravgonzalez99@gmail.com';

try {
    $stmt = $pdo->prepare("UPDATE users SET is_activated = 1, role = 'admin' WHERE email = :email");
    $stmt->execute([':email' => $email]);
    echo "Compte admin activé avec succès.";
} catch (PDOException $e) {
    echo "Erreur lors de l'activation : " . $e->getMessage();
}
?>
