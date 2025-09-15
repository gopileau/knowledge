<?php
require 'config/config.php';

try {
    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
    $stmt = $pdo->query('SELECT id, email, is_activated, activation_token FROM users ORDER BY id DESC LIMIT 1');
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "Dernier utilisateur inscrit:\n";
        echo "ID: ".$user['id']."\n";
        echo "Email: ".$user['email']."\n";
        echo "Activé: ".($user['is_activated'] ? 'Oui' : 'Non')."\n";
        echo "Token: ".$user['activation_token']."\n";
    } else {
        echo "Aucun utilisateur trouvé en base de données\n";
    }
} catch (PDOException $e) {
    die('Erreur: '.$e->getMessage());
}
