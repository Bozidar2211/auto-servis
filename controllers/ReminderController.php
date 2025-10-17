<?php
require_once __DIR__ . '/../models/Reminder.php';

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

$reminders = Reminder::getAllByCar($carId);

// Fallback ako nema podsetnika
if (!$reminders || !is_array($reminders)) {
    echo "Nema dostupnih podsetnika za ovo vozilo.";
    exit;
}
