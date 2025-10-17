<?php
require_once __DIR__ . '/../models/User.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    //Validacija praznih polja
    if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
        $_SESSION['error'] = "Sva polja su obavezna.";
        header('Location: ../views/register.php');
        exit;
    }

    //Validacija email formata
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Email nije validan.";
        header('Location: ../views/register.php');
        exit;
    }

    //Validacija dužine lozinke
    if (strlen($password) < 6) {
        $_SESSION['error'] = "Lozinka mora imati najmanje 6 karaktera.";
        header('Location: ../views/register.php');
        exit;
    }

    //Validacija poklapanja lozinki
    if ($password !== $confirm) {
        $_SESSION['error'] = "Lozinke se ne poklapaju.";
        header('Location: ../views/register.php');
        exit;
    }

    //Provera da li korisnik već postoji
    if (User::findByEmail($email)) {
        $_SESSION['error'] = "Korisnik sa tim emailom već postoji.";
        header('Location: ../views/register.php');
        exit;
    }

    //Kreiranje korisnika
    $success = User::create($username, $email, $password);

    if ($success) {
        $_SESSION['success'] = "Uspešna registracija. Možete se prijaviti.";
        header('Location: ../views/login.php');
        exit;
    } else {
        $_SESSION['error'] = "Greška pri registraciji.";
        header('Location: ../views/register.php');
        exit;
    }
}
