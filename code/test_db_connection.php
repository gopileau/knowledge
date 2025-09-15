<?php
require 'config/config.php';

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Tables dans la base de données :\n";
    foreach ($tables as $table) {
        echo $table . "\n";
    }
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
