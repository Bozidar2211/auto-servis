<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'config/db.php';
require_once 'controllers/RequestController.php';
require_once 'models/RequestModel.php';

$requestController = new RequestController($pdo);
$requestModel = new RequestModel($pdo);

$controller = $_GET['controller'] ?? '';
$action = $_GET['action'] ?? '';
$filter = $_GET['filter'] ?? 'active';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

// Kontroler za zahteve
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

        case 'decline':
            if (isset($_GET['id'])) {
            $requestController->decline($_GET['id'], $_SESSION['user']['id']);
            } else {
                echo "<div class='container mt-5'><div class='alert alert-danger'>Nedostaje ID zahteva.</div></div>";
            }
            break;

            default:
            echo "<div class='container mt-5'><div class='alert alert-warning'>Nepoznata akcija: <strong>" . htmlspecialchars($action) . "</strong></div></div>";
            }

// Kontroler za dodavanje servisa iz zahteva
} elseif ($controller === 'service') {
    switch ($action) {
        case 'createFromRequest':
            if (isset($_GET['id'])) {
                $request = $requestModel->getRequestById($_GET['id']);
                if ($request) {
                    $_SESSION['prefill_request'] = $request;
                    header("Location: views/add_service.php?car_id=" . $request['car_id']);
                    exit;
                } else {
                    echo "<div class='container mt-5'><div class='alert alert-danger'>Zahtev nije pronađen.</div></div>";
                }
            } else {
                echo "<div class='container mt-5'><div class='alert alert-danger'>Nedostaje ID zahteva.</div></div>";
            }
            break;

        default:
            echo "<div class='container mt-5'><div class='alert alert-warning'>Nepoznata akcija: <strong>" . htmlspecialchars($action) . "</strong></div></div>";
    }

// Nepoznat kontroler
} else {
    echo "<div class='container mt-5'><div class='alert alert-warning'>Nepoznat kontroler: <strong>" . htmlspecialchars($controller) . "</strong></div></div>";
}
