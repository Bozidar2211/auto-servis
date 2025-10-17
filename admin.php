<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: views/dashboard.php');
    exit;
}

$controller = $_GET['controller'] ?? '';
$action = $_GET['action'] ?? '';

switch ($controller) {
    case 'user':
        require_once __DIR__ . '/controllers/AdminUserController.php';
        $ctrl = new AdminUserController();
        break;
    case 'report':
        require_once __DIR__ . '/controllers/AdminReportController.php';
        $ctrl = new AdminReportController();
        break;
    default:
        die('Nepoznat kontroler.');
}

if (method_exists($ctrl, $action)) {
    $ctrl->$action();
} else {
    die('Nepoznata akcija.');
}
