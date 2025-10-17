<?php
require_once __DIR__ . '/../config/db.php';

class MechanicModel {
    private $pdo;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getRequests($mechanic_id) {
        $stmt = $this->conn->prepare("SELECT r.*, u.username AS user_name, c.model AS car_model 
                                      FROM requests r 
                                      JOIN users u ON r.user_id = u.id 
                                      JOIN cars c ON r.car_id = c.id 
                                      WHERE r.mechanic_id = ?");
        $stmt->execute([$mechanic_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function replyToRequest($request_id, $price, $date, $note) {
        $stmt = $this->conn->prepare("UPDATE requests 
                                      SET proposed_price = ?, proposed_date = ?, note = ?, status = 'answered' 
                                      WHERE id = ?");
        return $stmt->execute([$price, $date, $note, $request_id]);
    }
}
?>