<?php
namespace App\Models;

require_once __DIR__.'/Database.php';

class Order extends Database {
    protected $connection;

    public function __construct() {
        parent::__construct();
        $this->connection = $this->getConnection();
    }

    public function getOrdersByUserId($userId) {
        $stmt = $this->connection->prepare("
            SELECT p.id, p.user_id, p.course_id, p.purchase_date
            FROM purchases p
            WHERE p.user_id = ?
            ORDER BY p.purchase_date DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getOrderCount() {
        $stmt = $this->connection->prepare("SELECT COUNT(*) as count FROM purchases");
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }

    public function getRecentOrders($limit = 5) {
        $stmt = $this->connection->prepare("
            SELECT p.id, p.purchase_date as created_at, u.email as user_email
            FROM purchases p
            JOIN users u ON p.user_id = u.id
            ORDER BY p.purchase_date DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, (int)$limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
