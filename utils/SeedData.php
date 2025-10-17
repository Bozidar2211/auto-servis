<?php
require_once __DIR__ . '/../config/db.php';

class SeedData {
    public static function ensureAdminExists() {
        global $pdo;

        $email = 'admin@example.com';
        $username = 'Admin';
        $password = 'admin123';
        $role = 'admin';

        // Provera da li admin već postoji
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND role = ?");
        $stmt->execute([$email, $role]);
        $exists = $stmt->fetchColumn();

        if (!$exists) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $hashed, $role]);
        }
    }
}
