<?php
require 'config/config.php';

try {
    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
    $stmt = $pdo->query('DESCRIBE users');
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Structure de la table users:\n";
    foreach ($columns as $col) {
        echo "- ".$col['Field']." (".$col['Type'].")\n";
    }
} catch (PDOException $e) {
    die('Erreur: '.$e->getMessage());
}
