<?php
global $pdo;

$host = '127.0.0.1';
$port = '3307';
$dbname = 'auto_servis';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Greška pri konekciji sa bazom: " . $e->getMessage());
}
