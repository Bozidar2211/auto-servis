<?php
require_once __DIR__ . '/../config/db.php';

class Service {
    public static function getByCar($carId) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM services WHERE car_id = ? ORDER BY service_date DESC");
        $stmt->execute([$carId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function add($carId, $date, $description, $cost, $mileage) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO services (car_id, service_date, description, cost, mileage) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$carId, $date, $description, $cost, $mileage]);
    }
    public static function getById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

}
?>
