<?php
require_once __DIR__ . '/../models/Service.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../views/login.php');
    exit;
}

$carId = $_GET['car_id'] ?? null;

// ✅ Validacija car_id
if (!$carId || !is_numeric($carId)) {
    echo "ID vozila nije validan.";
    exit;
}

$services = Service::getByCar($carId);

// ✅ Fallback ako nema servisa
if (!$services || !is_array($services)) {
    echo "Nema dostupnih servisa za ovo vozilo.";
    exit;
}
