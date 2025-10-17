<?php
require_once __DIR__ . '/../models/Reminder.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../views/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carId = $_POST['car_id'] ?? null;
    $date = trim($_POST['reminder_date'] ?? '');
    $note = trim($_POST['note'] ?? '');

    // Validacija unosa
    if (!$carId || !is_numeric($carId)) {
        echo "ID vozila nije validan.";
        exit;
    }

    if (empty($date) || empty($note)) {
        echo "Datum i beleška su obavezni.";
        exit;
    }

    //Validacija formata datuma (YYYY-MM-DD)
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        echo "Datum mora biti u formatu YYYY-MM-DD.";
        exit;
    }

    //Validacija dužine beleške
    if (strlen($note) < 3 || strlen($note) > 255) {
        echo "Beleška mora imati između 3 i 255 karaktera.";
        exit;
    }

    $success = Reminder::add($carId, $date, $note);

    if ($success) {
        header("Location: ../views/dashboard.php");
        exit;
    } else {
        echo "Greška pri dodavanju podsetnika.";
    }
}
