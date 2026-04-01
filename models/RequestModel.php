<?php
class RequestModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($user_id, $mechanic_id, $car_id, $description) {
        $stmt = $this->conn->prepare("
            INSERT INTO requests (user_id, mechanic_id, car_id, description, status, created_at)
            VALUES (?, ?, ?, ?, 'pending', NOW())
        ");
        $stmt->execute([$user_id, $mechanic_id, $car_id, $description]);
    }

    public function getAvailableMechanics() {
        $stmt = $this->conn->query("SELECT id, username FROM users WHERE role = 'mechanic'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserCars($user_id) {
        $stmt = $this->conn->prepare("SELECT id, model FROM cars WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserRequestsWithReplies($user_id, $filter = 'active') {
    $query = "
        SELECT r.*, 
               u.username AS mechanic_name, 
               c.model AS car_model, 
               c.brand AS car_brand
        FROM requests r
        JOIN users u ON r.mechanic_id = u.id
        JOIN cars c ON r.car_id = c.id
        WHERE r.user_id = ?
    ";

    if ($filter === 'active') {
        $query .= " AND r.status IN ('pending', 'answered', 'scheduled')";
    }

    $query .= " ORDER BY r.created_at DESC";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public function scheduleRequest($request_id) {
        $stmt = $this->conn->prepare("UPDATE requests SET status = 'scheduled' WHERE id = ?");
        $stmt->execute([$request_id]);
    }
    public function getRequestById($request_id) {
    $stmt = $this->conn->prepare("
        SELECT r.*, c.model AS car_model
        FROM requests r
        JOIN cars c ON r.car_id = c.id
        WHERE r.id = ?
    ");
    $stmt->execute([$request_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function updateStatus($requestId, $status) {
    $stmt = $this->conn->prepare("UPDATE requests SET status = ? WHERE id = ?");
    return $stmt->execute([$status, $requestId]);
}



}
