<?php
require_once __DIR__ . '/../models/Car.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../views/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user']['id'];
    $brand = trim($_POST['brand'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $year = $_POST['year'] ?? '';
    $registration = strtoupper(trim($_POST['registration'] ?? ''));

    //Validacija unosa
    if (empty($brand) || empty($model) || empty($year) || empty($registration)) {
        $_SESSION['error'] = "Sva polja su obavezna.";
        header('Location: ../views/add_car.php');
        exit;
    }

    if (!is_numeric($year) || $year < 1900 || $year > date('Y')) {
        $_SESSION['error'] = "Godina mora biti broj između 1900 i " . date('Y') . ".";
        header('Location: ../views/add_car.php');
        exit;
    }

    if (!preg_match('/^[A-Z0-9\-]{5,10}$/', $registration)) {
        $_SESSION['error'] = "Registracija mora sadržati 5–10 karaktera (slova, brojevi, crtica).";
        header('Location: ../views/add_car.php');
        exit;
    }

    $success = Car::add($userId, $brand, $model, $year, $registration);

    if ($success) {
        $_SESSION['success'] = "Automobil je uspešno dodat.";
        header('Location: ../views/dashboard.php');
        exit;
    } else {
        $_SESSION['error'] = "Greška pri dodavanju automobila.";
        header('Location: ../views/add_car.php');
        exit;
    }
}
