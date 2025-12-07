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
$filter = $_GET['filter'] ?? 'active';

switch ($action) {
    case 'dashboard':
        $controller->dashboard($_SESSION['user']['id'], $filter);
        break;

    case 'showReplyForm':
        if (isset($_GET['id'])) {
            $controller->showReplyForm($_GET['id']);
        } else {
            echo "<div class='container mt-5'><div class='alert alert-danger'>Nedostaje ID zahteva.</div></div>";
        }
        break;

    case 'reply':
    header('Content-Type: application/json; charset=utf-8');

    if (isset($_POST['request_id'], $_POST['price'], $_POST['date'])) {
        $success = $controller->reply(
            $_POST['request_id'],
            $_POST['price'],
            $_POST['date'],
            $_POST['note'] ?? ''
        );

        echo json_encode([
            'success' => $success,
            'message' => $success ? 'Ponuda je uspješno poslana!' : 'Greška pri slanju ponude'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Greška: Nedostaju obavezni podaci.'
        ]);
    }
    exit;
        break;

    case 'markCompleted':
        if (isset($_GET['id'])) {
            $controller->markCompleted($_GET['id']);
        } else {
            echo "<div class='container mt-5'><div class='alert alert-danger'>Nedostaje ID zahteva.</div></div>";
        }
        break;

    default:
        echo "<div class='container mt-5'><div class='alert alert-warning'>Nepoznata akcija: <strong>" . htmlspecialchars($action) . "</strong></div></div>";
}
