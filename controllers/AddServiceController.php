<?php
require_once __DIR__ . '/../models/Service.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../views/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carId = $_POST['car_id'] ?? null;
    $date = trim($_POST['service_date'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $cost = $_POST['cost'] ?? '';
    $mileage = $_POST['mileage'] ?? '';

    //Validacija ID-a vozila
    if (!$carId || !is_numeric($carId)) {
        echo "ID vozila nije validan.";
        exit;
    }

    //Validacija datuma
    if (empty($date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        echo "Datum servisa mora biti u formatu YYYY-MM-DD.";
        exit;
    }

    // Validacija opisa
    if (empty($description) || strlen($description) < 3 || strlen($description) > 255) {
        echo "Opis mora imati između 3 i 255 karaktera.";
        exit;
    }

    //Validacija kilometraže
    if (!is_numeric($mileage) || $mileage < 0) {
        echo "Kilometraža mora biti pozitivan broj.";
        exit;
    }

    //Validacija cene
    if (!is_numeric($cost) || $cost < 0) {
        echo "Cena mora biti pozitivan broj.";
        exit;
    }

    $success = Service::add($carId, $date, $description, $cost, $mileage);

    if ($success) {
        header("Location: ../views/services.php?car_id=$carId");
        exit;
    } else {
        echo "Greška pri dodavanju servisa.";
    }
}
