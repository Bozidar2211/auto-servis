<?php
require_once __DIR__ . '/../config/db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../views/login.php');
    exit;
}

$type = $_POST['type'] ?? null;
$id = $_POST['id'] ?? null;
$carId = $_POST['car_id'] ?? null;

//Osnovna validacija
if (!$type || !$id || !$carId || !is_numeric($id) || !is_numeric($carId)) {
    echo "Podaci nisu validni.";
    exit;
}

global $pdo;

switch ($type) {
    case 'service':
        $date = $_POST['service_date'] ?? '';
        $desc = trim($_POST['description'] ?? '');
        $mileage = $_POST['mileage'] ?? '';
        $cost = $_POST['cost'] ?? '';

        //Validacija servisa
        if (
            empty($date) || empty($desc) ||
            !is_numeric($mileage) || $mileage < 0 ||
            !is_numeric($cost) || $cost < 0
        ) {
            echo "Neispravni podaci za servis.";
            exit;
        }

        $stmt = $pdo->prepare("UPDATE services SET service_date = ?, description = ?, mileage = ?, cost = ? WHERE id = ?");
        $stmt->execute([$date, $desc, $mileage, $cost, $id]);
        header("Location: ../views/services.php?car_id=$carId");
        break;

    case 'modification':
        $date = $_POST['mod_date'] ?? '';
        $desc = trim($_POST['description'] ?? '');
        $cost = $_POST['cost'] ?? '';

        //Validacija modifikacije
        if (
            empty($date) || empty($desc) ||
            !is_numeric($cost) || $cost < 0
        ) {
            echo "Neispravni podaci za modifikaciju.";
            exit;
        }

        $stmt = $pdo->prepare("UPDATE modifications SET mod_date = ?, description = ?, cost = ? WHERE id = ?");
        $stmt->execute([$date, $desc, $cost, $id]);
        header("Location: ../views/modifications.php?car_id=$carId");
        break;

    case 'reminder':
        $date = $_POST['reminder_date'] ?? '';
        $note = trim($_POST['note'] ?? '');

        //Validacija podsetnika
        if (empty($date) || empty($note)) {
            echo "Neispravni podaci za podsetnik.";
            exit;
        }

        $stmt = $pdo->prepare("UPDATE reminders SET reminder_date = ?, note = ? WHERE id = ?");
        $stmt->execute([$date, $note, $id]);
        header("Location: ../views/reminders.php?car_id=$carId");
        break;

    default:
        echo "Nepoznat tip izmene.";
}
exit;
