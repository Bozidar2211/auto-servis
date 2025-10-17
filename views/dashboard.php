<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
if ($_SESSION['user']['role'] === 'admin') {
    header('Location: /auto-servis/admin.php?controller=user&action=index');
    exit;
}


require_once __DIR__ . '/../controllers/CarController.php';
require_once __DIR__ . '/../models/Reminder.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dismiss_all_reminders'])) {
    $_SESSION['dismissed_today_reminders'] = true;
}

$remindersToday = [];
if (!isset($_SESSION['dismissed_today_reminders'])) {
    $remindersToday = Reminder::getTodayByUser($_SESSION['user']['id']);
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="bg-dark text-white p-3">
        <div class="container">
            <h1 class="h3">Dobrodošao, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>!</h1>
            <p>Email: <?php echo htmlspecialchars($_SESSION['user']['email']); ?> | Uloga: <?php echo htmlspecialchars($_SESSION['user']['role']); ?></p>
        </div>
    </header>

    <div class="container mt-4">
        <?php if (!empty($remindersToday)): ?>
            <div class="reminder-box">
                <strong>📌 Podsetnik za danas:</strong>
                <ul class="mt-2">
                    <?php foreach ($remindersToday as $reminder): ?>
                        <li>
                            <?php echo htmlspecialchars($reminder['brand'] . ' ' . $reminder['model'] . ': ' . $reminder['note']); ?>
                            <form method="POST" action="../controllers/DeleteController.php" class="d-inline" data-confirm="Da li ste sigurni da želite da obrišete ovaj podsetnik?">
                                <input type="hidden" name="type" value="reminder">
                                <input type="hidden" name="id" value="<?php echo $reminder['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger ms-2">Obriši</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <form method="POST" class="mt-2">
                    <input type="hidden" name="dismiss_all_reminders" value="1">
                    <button type="submit" class="btn btn-warning">U redu</button>
                </form>
            </div>
        <?php endif; ?>

        <h3 class="mt-4">Moji automobili:</h3>
        <?php if (empty($cars)): ?>
            <p>Nemate registrovanih automobila.</p>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach ($cars as $car): ?>
                    <li class="list-group-item">
                        <strong><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model'] . ' (' . $car['year'] . ') - ' . $car['registration']); ?></strong><br>
                        <a href="services.php?car_id=<?php echo $car['id']; ?>">Servisi</a> |
                        <a href="add_service.php?car_id=<?php echo $car['id']; ?>">Dodaj servis</a> |
                        <a href="modifications.php?car_id=<?php echo $car['id']; ?>">Modifikacije</a> |
                        <a href="add_modification.php?car_id=<?php echo $car['id']; ?>">Dodaj modifikaciju</a> |
                        <a href="stats.php?car_id=<?php echo $car['id']; ?>">Statistika</a> |
                        <a href="reminders.php?car_id=<?php echo $car['id']; ?>">Pregled podsetnika</a> |
                        <a href="add_reminder.php?car_id=<?php echo $car['id']; ?>">Dodaj podsetnik</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <div class="mt-4">
            <a href="add_car.php" class="btn btn-success">Dodaj automobil</a>
            <a href="upcoming_reminders.php" class="btn btn-info ms-2">Nadolazeći podsetnici</a>
            <a href="/auto-servis/user.php?controller=request&action=showForm" class="btn btn-success">📨 Novi servisni zahtev</a>
            <a href="/auto-servis/user.php?controller=request&action=myRequests" class="btn btn-outline-primary">📋 Moji zahtevi</a>
            <a href="logout.php" class="btn btn-outline-danger ms-2">Odjavi se</a>
        </div>
    </div>

    <footer class="bg-light text-center p-3 mt-5">
        &copy; <?php echo date('Y'); ?> Božidar AutoApp
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>
