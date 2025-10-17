<?php
session_start();
require_once __DIR__ . '/../models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    //Validacija unosa
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Email nije validan.";
        header('Location: /auto-servis/views/login.php');
        exit;
    }

    if (strlen($password) < 6) {
        $_SESSION['error'] = "Lozinka mora imati najmanje 6 karaktera.";
        header('Location: /auto-servis/views/login.php');
        exit;
    }

    $user = User::findByEmail($email);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;

        if ($user['role'] === 'admin') {
            header('Location: /auto-servis/admin.php?controller=user&action=dashboard');
        } else if($user['role'] === 'mechanic'){
            header('Location: /auto-servis/mechanic.php?controller=mechanic&action=dashboard');
        }
        else{
            header('Location: /auto-servis/views/dashboard.php');
        }
        exit;
    } else {
        $_SESSION['error'] = "Neispravni podaci.";
        header('Location: /auto-servis/views/login.php');
        exit;
    }
}
