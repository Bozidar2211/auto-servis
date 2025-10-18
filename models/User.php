<?php
require_once __DIR__ . '/../config/db.php';

class User {
    public static function findByEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($username, $email, $password, $role = 'user') {
        if (
            empty($username) ||
            !filter_var($email, FILTER_VALIDATE_EMAIL) ||
            strlen($password) < 6 ||
            !in_array($role, ['user', 'admin'])
        ) {
            return false;
        }

        global $pdo;
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$username, $email, $hashed, $role]);
    }

    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query("SELECT id, username, email, role FROM users ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        if (!is_numeric($id)) {
            return false;
        }
        global $pdo;
        $stmt = $pdo->prepare("SELECT id, username, email, role FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($data) {
        if (
            empty($data['username']) ||
            !filter_var($data['email'], FILTER_VALIDATE_EMAIL) ||
            !in_array($data['role'], ['user', 'admin']) ||
            !is_numeric($data['id'])
        ) {
            return false;
        }

        global $pdo;
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
        return $stmt->execute([
            $data['username'],
            $data['email'],
            $data['role'],
            $data['id']
        ]);
    }

  public static function delete($id) {
    if (!is_numeric($id)) {
        return false;
    }

    global $pdo;

    try {
        // 1. Obriši servise za korisnikove automobile
        $pdo->prepare("DELETE FROM services WHERE car_id IN (SELECT id FROM cars WHERE user_id = ?)")->execute([$id]);

        // 2. Obriši podsetnike za korisnikove automobile
        $pdo->prepare("DELETE FROM reminders WHERE car_id IN (SELECT id FROM cars WHERE user_id = ?)")->execute([$id]);

        // 3. Obriši modifikacije za korisnikove automobile
        $pdo->prepare("DELETE FROM modifications WHERE car_id IN (SELECT id FROM cars WHERE user_id = ?)")->execute([$id]);

        // 4. Obriši zahteve koje je korisnik poslao kao vlasnik automobila
        $pdo->prepare("DELETE FROM requests WHERE user_id = ?")->execute([$id]);

        // 5. Obriši zahteve gde je korisnik mehaničar
        $pdo->prepare("DELETE FROM requests WHERE mechanic_id = ?")->execute([$id]);

        // 6. Obriši automobile korisnika
        $pdo->prepare("DELETE FROM cars WHERE user_id = ?")->execute([$id]);

        // 7. Na kraju obriši korisnika
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);

    } catch (PDOException $e) {
        error_log("Greška pri brisanju korisnika: " . $e->getMessage());
        return false;
    }
}




}
