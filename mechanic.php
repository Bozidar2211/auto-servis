<?php
session_start();
require_once __DIR__ . '/config/db.php';
require_once 'controllers/MechanicController.php';

// Provera da li je mehaničar ulogovan
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'mechanic') {
    header("Location: login.php");
    exit;
}

$controller = new MechanicController($pdo);
$action = $_GET['action'] ?? 'dashboard';

switch ($action) {
    case 'dashboard':
        $controller->dashboard($_SESSION['user']['id']);
        break;

    case 'showReplyForm':
        if (isset($_GET['id'])) {
            $controller->showReplyForm($_GET['id']);
        } else {
            echo "<div class='container mt-5'><div class='alert alert-danger'>Nedostaje ID zahteva.</div></div>";
        }
        break;

    case 'reply':
        if (
            isset($_POST['request_id']) &&
            isset($_POST['price']) &&
            isset($_POST['date'])
        ) {
            $controller->reply(
                $_POST['request_id'],
                $_POST['price'],
                $_POST['date'],
                $_POST['note'] ?? ''
            );
        } else {
            echo "<div class='container mt-5'><div class='alert alert-danger'>Greška: Nedostaju obavezni podaci.</div></div>";
        }
        break;

    default:
        echo "<div class='container mt-5'><div class='alert alert-warning'>Nepoznata akcija: <strong>" . htmlspecialchars($action) . "</strong></div></div>";
}
