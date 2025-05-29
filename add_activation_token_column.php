<?php
require 'config/config.php';

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    
    // Ajouter la colonne activation_token
    $sql = "ALTER TABLE users 
            ADD COLUMN activation_token VARCHAR(255) AFTER is_activated";
    
    $pdo->exec($sql);
    echo "Colonne activation_token ajoutée avec succès\n";
} catch (PDOException $e) {
    die('Erreur: ' . $e->getMessage());
}
