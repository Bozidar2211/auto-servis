<?php
session_start();
require_once 'config/db.php';
require_once 'controllers/RequestController.php';

$requestController = new RequestController($pdo);

$controller = $_GET['controller'] ?? '';
$action = $_GET['action'] ?? '';
$filter = $_GET['filter'] ?? 'active';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

if ($controller === 'request') {
    switch ($action) {
        case 'showForm':
            $requestController->showForm($_SESSION['user']['id']);
            break;

        case 'submit':
            $requestController->submit(
                $_SESSION['user']['id'],
                $_POST['mechanic_id'],
                $_POST['car_id'],
                $_POST['description']
            );
            break;

        case 'myRequests':
            $requestController->myRequests($_SESSION['user']['id'], $filter);
            break;

        case 'schedule':
            if (isset($_GET['id'])) {
                $requestController->schedule($_GET['id']);
            } else {
                echo "<div class='container mt-5'><div class='alert alert-danger'>Nedostaje ID zahteva.</div></div>";
            }
            break;

        case 'confirmation':
            $requestController->confirmation();
            break;

        default:
            echo "<div class='container mt-5'><div class='alert alert-warning'>Nepoznata akcija: <strong>" . htmlspecialchars($action) . "</strong></div></div>";
    }
} else {
    echo "<div class='container mt-5'><div class='alert alert-warning'>Nepoznat kontroler: <strong>" . htmlspecialchars($controller) . "</strong></div></div>";
}
