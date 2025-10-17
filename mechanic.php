<?php
session_start();
require_once __DIR__ . '/config/db.php';
require_once 'controllers/MechanicController.php';

$controller = new MechanicController($pdo);

$action = $_GET['action'] ?? 'dashboard';

switch ($action) {
    case 'dashboard':
        $controller->dashboard($_SESSION['user']['id']);
        break;
    case 'reply':
        $controller->reply($_POST['request_id'], $_POST['price'], $_POST['date'], $_POST['note']);
        break;
    case 'showReplyForm':
        $controller->showReplyForm($_GET['id']);
        break;
    default:
        echo "Nepoznata akcija.";
}
