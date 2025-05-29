<?php
function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $host     = 'localhost';
        $dbname   = 'knowledge_learning';
        $username = 'root';
        $password = '';

        try {
            $pdo = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8",
                $username,
                $password,
                [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]
            );
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }
    return $pdo;
}
?>
