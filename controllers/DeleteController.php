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

if (!$type || !$id) {
    echo "Podaci nisu prosleđeni.";
    exit;
}

global $pdo;

switch ($type) {
    case 'service':
        $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: ../views/services.php?car_id=$carId");
        break;

    case 'modification':
        $stmt = $pdo->prepare("DELETE FROM modifications WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: ../views/modifications.php?car_id=$carId");
        break;

    case 'reminder':
        $stmt = $pdo->prepare("DELETE FROM reminders WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: ../views/dashboard.php");
        break;

    default:
        echo "Nepoznat tip brisanja.";
}
exit;
