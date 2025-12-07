    <?php
    require_once __DIR__ . '/../config/db.php';

    class Report {
        public static function getSystemStats() {
            global $pdo;
            $stats = [];

            $stats['user_count'] = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
            $stats['car_count'] = $pdo->query("SELECT COUNT(*) FROM cars")->fetchColumn();
            $stats['service_count'] = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
            $stats['mod_count'] = $pdo->query("SELECT COUNT(*) FROM modifications")->fetchColumn();
            $stats['reminder_count'] = $pdo->query("SELECT COUNT(*) FROM reminders")->fetchColumn();

            return $stats;
        }

      public static function getCostsByUser() {
    global $pdo;
    $stmt = $pdo->query("
        SELECT 
            u.username,
            u.id,
            (
                SELECT COALESCE(SUM(s.cost), 0)
                FROM services s
                JOIN cars c ON s.car_id = c.id
                WHERE c.user_id = u.id
            ) + (
                SELECT COALESCE(SUM(m.total_cost), 0)
                FROM modifications m
                JOIN cars c ON m.car_id = c.id
                WHERE c.user_id = u.id
            ) AS total_cost
        FROM users u
        ORDER BY total_cost DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

        public static function getTopServiceTypes() {
            global $pdo;
            $stmt = $pdo->query("
                SELECT description, COUNT(*) AS count
                FROM services
                GROUP BY description
                ORDER BY count DESC
                LIMIT 10
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
