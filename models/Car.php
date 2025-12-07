<?php
require_once __DIR__ . '/../config/db.php';

class Car {
    public static function getByUser($userId) {
        if (!is_numeric($userId)) {
            return [];
        }

        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM cars WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public static function getById($id) {
    if (!is_numeric($id)) {
        return null;
    }

    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    public static function add($userId, $brand, $model, $year, $registration) {
        //Validacija unosa
        if (
            !is_numeric($userId) ||
            empty($brand) || empty($model) || empty($registration) ||
            !is_numeric($year) || $year < 1900 || $year > date('Y') ||
            !preg_match('/^[A-Z0-9\-]{5,10}$/', strtoupper($registration))
        ) {
            return false;
        }

        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO cars (user_id, brand, model, year, registration) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$userId, $brand, $model, $year, strtoupper($registration)]);
    }
}
