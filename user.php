<?php
session_start();
require_once 'config/db.php';
require_once 'controllers/RequestController.php';

$requestController = new RequestController($pdo);

$controller = $_GET['controller'] ?? '';
$action = $_GET['action'] ?? '';

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
    $requestController->myRequests($_SESSION['user']['id']);
    break;
        case 'confirmation':
            $requestController->confirmation();
            break;
        default:
            echo "Nepoznata akcija.";
    }
} else {
    echo "Nepoznat kontroler.";
}
