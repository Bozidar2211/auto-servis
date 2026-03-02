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
$userId = $_SESSION['user']['id'] ?? null;

if (!$type) {
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

    case 'car':
        if (!$carId || !$userId) {
            echo "Nedostaju podaci za brisanje automobila.";
            exit;
        }

        // Provera da li auto ima istoriju
        $stmt = $pdo->prepare("
            SELECT 
                (SELECT COUNT(*) FROM services WHERE car_id = ?) +
                (SELECT COUNT(*) FROM modifications WHERE car_id = ?) +
                (SELECT COUNT(*) FROM reminders WHERE car_id = ?) AS total
        ");
        $stmt->execute([$carId, $carId, $carId]);
        $total = $stmt->fetchColumn();

        if ($total == 0) {
            // nema istorije → briši auto
            $delete = $pdo->prepare("DELETE FROM cars WHERE id = ? AND user_id = ?");
            $delete->execute([$carId, $userId]);
        } else {
            // ima istoriju → samo odveži od usera
            $update = $pdo->prepare("UPDATE cars SET user_id = NULL WHERE id = ? AND user_id = ?");
            $update->execute([$carId, $userId]);
        }

        header("Location: ../views/dashboard.php");
        break;

    default:
        echo "Nepoznat tip brisanja.";
}
exit;
