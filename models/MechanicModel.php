<?php
require_once __DIR__ . '/../config/db.php';

class MechanicModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Dohvatanje zahteva sa opcionalnim filterom
    public function getRequests($mechanic_id, $filter = 'active') {
        $query = "SELECT r.*, u.username AS user_name, c.model AS car_model 
                  FROM requests r 
                  JOIN users u ON r.user_id = u.id 
                  JOIN cars c ON r.car_id = c.id 
                  WHERE r.mechanic_id = ?";

        if ($filter === 'active') {
            $query .= " AND r.status IN ('pending', 'answered', 'scheduled')";
        }

        $query .= " ORDER BY r.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$mechanic_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Odgovor na zahtev
    public function replyToRequest($request_id, $price, $date, $note) {
        $stmt = $this->conn->prepare("
            UPDATE requests 
            SET proposed_price = ?, proposed_date = ?, note = ?, status = 'answered' 
            WHERE id = ?
        ");
        return $stmt->execute([$price, $date, $note, $request_id]);
    }

    // Označavanje zahteva kao završenog
    public function markRequestCompleted($request_id) {
        $stmt = $this->conn->prepare("
            UPDATE requests 
            SET status = 'completed' 
            WHERE id = ?
        ");
        return $stmt->execute([$request_id]);
    }
}
