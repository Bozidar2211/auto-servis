<?php
require_once __DIR__ . '/../models/Car.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../views/login.php');
    exit;
}

$userId = $_SESSION['user']['id'];
$cars = Car::getByUser($userId);
