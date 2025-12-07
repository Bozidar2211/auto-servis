<?php
require_once __DIR__ . '/../models/Service.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../views/login.php');
    exit;
}

$carId = $_GET['car_id'] ?? null;

//Validacija car_id
if (!$carId || !is_numeric($carId)) {
    echo "ID vozila nije validan.";
    exit;
}

// Povuci servise
$services = Service::getByCar($carId);

// Uključi view
require_once __DIR__ . '/../views/services.php';

