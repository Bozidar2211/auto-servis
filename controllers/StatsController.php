<?php
require_once __DIR__ . '/../config/db.php';

$carId = $_GET['car_id'] ?? null;
if (!$carId) {
    echo "ID vozila nije prosleđen.";
    exit;
}

global $pdo;

// Ukupan trošak servisa
$stmt = $pdo->prepare("SELECT SUM(cost) AS total_service_cost, COUNT(*) AS service_count, MAX(cost) AS max_service_cost FROM services WHERE car_id = ?");
$stmt->execute([$carId]);
$serviceStats = $stmt->fetch(PDO::FETCH_ASSOC);

// Ukupan trošak modifikacija
$stmt = $pdo->prepare("SELECT SUM(cost) AS total_mod_cost, COUNT(*) AS mod_count FROM modifications WHERE car_id = ?");
$stmt->execute([$carId]);
$modStats = $stmt->fetch(PDO::FETCH_ASSOC);

// Podaci o vozilu
$stmt = $pdo->prepare("SELECT brand, model, year, registration FROM cars WHERE id = ?");
$stmt->execute([$carId]);
$car = $stmt->fetch(PDO::FETCH_ASSOC);
