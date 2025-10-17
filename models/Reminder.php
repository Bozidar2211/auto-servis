<?php
require_once __DIR__ . '/../config/db.php';

class Reminder {
    public static function getTodayByUser($userId) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT r.*, c.brand, c.model
            FROM reminders r
            JOIN cars c ON r.car_id = c.id
            WHERE r.reminder_date = CURDATE() AND c.user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function add($carId, $date, $note) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO reminders (car_id, reminder_date, note) VALUES (?, ?, ?)");
        return $stmt->execute([$carId, $date, $note]);
    }
    public static function getAllByCar($carId) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT r.*, c.brand, c.model
        FROM reminders r
        JOIN cars c ON r.car_id = c.id
        WHERE r.car_id = ?
        ORDER BY r.reminder_date DESC
    ");
    $stmt->execute([$carId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public static function getById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM reminders WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
public static function getUpcomingByUser($userId) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT r.*, c.brand, c.model
        FROM reminders r
        JOIN cars c ON r.car_id = c.id
        WHERE c.user_id = ?
        ORDER BY r.reminder_date ASC
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



}
?>
