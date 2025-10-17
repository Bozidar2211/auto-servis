<?php
session_start();
require_once __DIR__ . '/../models/Reminder.php';

$reminders = Reminder::getUpcomingByUser($_SESSION['user']['id']);
$today = date('Y-m-d');
$weekAhead = date('Y-m-d', strtotime('+7 days'));
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Nadolazeći podsetnici</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header class="bg-dark text-white p-3">
    <div class="container">
        <h1 class="h3">📅 Nadolazeći podsetnici</h1>
    </div>
</header>

<div class="container mt-4">
    <?php if (empty($reminders)): ?>
        <p>Nema podsetnika.</p>
    <?php else: ?>
        <ul class="list-group">
            <?php foreach ($reminders as $r): ?>
                <?php
                    $date = $r['reminder_date'];
                    if ($date === $today) {
                        $label = '🟢 Danas';
                    } elseif ($date > $today && $date <= $weekAhead) {
                        $label = '🟡 Uskoro';
                    } elseif ($date < $today) {
                        $label = '🔴 Propušteno';
                    } else {
                        $label = '📌 Kasnije';
                    }
                ?>
                <li class="list-group-item">
                    <?php echo "$label — " . htmlspecialchars($date . ' — ' . $r['brand'] . ' ' . $r['model'] . ': ' . $r['note']); ?>
                    <a href="edit_reminder.php?id=<?php echo $r['id']; ?>" class="btn btn-sm btn-outline-primary ms-2">Izmeni</a>
                    <form method="POST" action="../controllers/DeleteController.php" class="d-inline" data-confirm="Da li ste sigurni da želite da obrišete ovaj podsetnik?">
                        <input type="hidden" name="type" value="reminder">
                        <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger ms-2">Obriši</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <div class="mt-3">
        <a href="dashboard.php" class="btn btn-secondary">Nazad na dashboard</a>
    </div>
</div>

<footer class="bg-light text-center p-3 mt-5">
    &copy; <?php echo date('Y'); ?> Božidar AutoApp
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/main.js"></script>
</body>
</html>
