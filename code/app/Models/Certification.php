<?php

namespace App\Models;

use App\Models\Database;
use PDO;

class Certification
{
    private $db;

    public function __construct()
    {
        // Ici $this->db est une instance PDO
        $this->db = (new Database())->getConnection();
    }

    public function getAllCertifications()
    {
        $sql = "SELECT * FROM certifications";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCertificationById($id)
    {
        $sql = "SELECT * FROM certifications WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCertificationCount() {
        $sql = "SELECT COUNT(*) AS count FROM certifications";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }
}
