<?php
require_once __DIR__ . '/../models/Modification.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../views/login.php');
    exit;
}

$carId = $_GET['car_id'] ?? null;

if (!$carId) {
    echo "ID vozila nije prosleđen.";
    exit;
}

$modifications = Modification::getByCar($carId);
