<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: ../../login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];
$car_id = $_POST['car_id'];
$mechanic_id = $_POST['mechanic_id'];
$description = $_POST['description'];

$stmt = $pdo->prepare("
    INSERT INTO requests (user_id, mechanic_id, car_id, description, status, created_at)
    VALUES (?, ?, ?, ?, 'pending', NOW())
");
$stmt->execute([$user_id, $mechanic_id, $car_id, $description]);

header("Location: request_confirmation.php");
exit;
