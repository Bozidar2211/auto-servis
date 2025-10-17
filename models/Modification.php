<?php
require_once __DIR__ . '/../config/db.php';

class Modification {
    public static function getByCar($carId) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM modifications WHERE car_id = ? ORDER BY mod_date DESC");
        $stmt->execute([$carId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function add($carId, $date, $description, $cost) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO modifications (car_id, mod_date, description, cost) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$carId, $date, $description, $cost]);
    }
    public static function getById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM modifications WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

}
?>
