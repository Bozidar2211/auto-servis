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

// Učitaj automobile korisnika
$cars = Car::getByUser($_SESSION['user']['id']);

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Auto Servis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <!-- Animated Background -->
    <div class="animated-bg">
        <div class="carbon-fiber"></div>
        <div class="gradient-overlay"></div>
    </div>

    <!-- Header -->
    <header class="modern-header">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="brand-section">
                    <i class="fas fa-car-side brand-icon"></i>
                    <h1 class="brand-title mb-0">Auto Servis</h1>
                </div>
                <div class="user-section">
                    <div class="user-info">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="user-details">
                            <span class="user-name"><?php echo htmlspecialchars($_SESSION['user']['username']); ?></span>
                            <span class="user-email"><?php echo htmlspecialchars($_SESSION['user']['email']); ?></span>
                        </div>
                    </div>
                    <a href="logout.php" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid px-4 py-4">
        <!-- Welcome Banner -->
        <div class="welcome-banner fade-in">
            <div class="welcome-content">
                <h2>Dobrodošao nazad, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>! 👋</h2>
                <p>Evo pregleda tvojih vozila i aktivnosti</p>
            </div>
            <div class="welcome-stats">
                <div class="stat-badge">
                    <i class="fas fa-car"></i>
                    <div>
                        <span class="stat-number"><?php echo count($cars); ?></span>
                        <span class="stat-label">Vozila</span>
                    </div>
                </div>
                <div class="stat-badge">
                    <i class="fas fa-bell"></i>
                    <div>
                        <span class="stat-number"><?php echo count($remindersToday); ?></span>
                        <span class="stat-label">Podsetnika</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reminders Alert -->
        <?php if (!empty($remindersToday)): ?>
            <div class="reminder-alert fade-in">
                <div class="reminder-header">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-bell"></i>
                        <h5 class="mb-0 ms-2">Podsetnici za danas</h5>
                    </div>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="dismiss_all_reminders" value="1">
                        <button type="submit" class="btn-dismiss">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>
                </div>
                <div class="reminder-list">
                    <?php foreach ($remindersToday as $reminder): ?>
                        <div class="reminder-item">
                            <div class="reminder-content">
                                <i class="fas fa-calendar-check"></i>
                                <span><?php echo htmlspecialchars($reminder['brand'] . ' ' . $reminder['model'] . ': ' . $reminder['note']); ?></span>
                            </div>
                            <form method="POST" action="../controllers/DeleteController.php" class="d-inline" data-confirm="Da li ste sigurni da želite da obrišete ovaj podsetnik?">
                                <input type="hidden" name="type" value="reminder">
                                <input type="hidden" name="id" value="<?php echo $reminder['id']; ?>">
                                <button type="submit" class="btn-remove">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="quick-actions fade-in" style="animation-delay: 0.1s;">
            <a href="add_car.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <div class="action-content">
                    <h6>Dodaj vozilo</h6>
                    <p>Registruj novo vozilo</p>
                </div>
            </a>
            <a href="/auto-servis/user.php?controller=request&action=showForm" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div class="action-content">
                    <h6>Servisni zahtev</h6>
                    <p>Pošalji novi zahtev</p>
                </div>
            </a>
            <a href="/auto-servis/user.php?controller=request&action=myRequests" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="action-content">
                    <h6>Moji zahtevi</h6>
                    <p>Pregled zahteva</p>
                </div>
            </a>
            <a href="upcoming_reminders.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="action-content">
                    <h6>Podsetnici</h6>
                    <p>Nadolazeći događaji</p>
                </div>
            </a>
        </div>

        <!-- Cars Section -->
        <div class="section-header fade-in" style="animation-delay: 0.2s;">
            <h3><i class="fas fa-car me-2"></i>Moja vozila</h3>
        </div>

        <?php if (empty($cars)): ?>
            <div class="empty-state fade-in" style="animation-delay: 0.3s;">
                <i class="fas fa-car-side"></i>
                <h4>Nemate registrovanih vozila</h4>
                <p>Dodajte svoje prvo vozilo da biste počeli sa praćenjem servisa</p>
                <a href="add_car.php" class="btn-primary-custom">
                    <i class="fas fa-plus me-2"></i>Dodaj prvo vozilo
                </a>
            </div>
        <?php else: ?>
            <div class="cars-grid">
                <?php foreach ($cars as $index => $car): ?>
                    <div class="car-card fade-in" style="animation-delay: <?php echo 0.3 + ($index * 0.1); ?>s;">
                        <div class="car-header">
                            <div class="car-icon">
                                <i class="fas fa-car"></i>
                            </div>
                            <div class="car-info">
                                <h5><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h5>
                                <p class="car-meta"><?php echo htmlspecialchars($car['year'] . ' • ' . $car['registration']); ?></p>
                            </div>
                        </div>
                        <div class="car-actions">
                            <a href="services.php?car_id=<?php echo $car['id']; ?>" class="action-btn" title="Servisi">
                                <i class="fas fa-wrench"></i>
                                <span>Servisi</span>
                            </a>
                            <a href="add_service.php?car_id=<?php echo $car['id']; ?>" class="action-btn" title="Dodaj servis">
                                <i class="fas fa-plus"></i>
                                <span>Dodaj</span>
                            </a>
                            <a href="modifications.php?car_id=<?php echo $car['id']; ?>" class="action-btn" title="Modifikacije">
                                <i class="fas fa-cogs"></i>
                                <span>Mods</span>
                            </a>
                            <a href="stats.php?car_id=<?php echo $car['id']; ?>" class="action-btn" title="Statistika">
                                <i class="fas fa-chart-line"></i>
                                <span>Stats</span>
                            </a>
                            <a href="reminders.php?car_id=<?php echo $car['id']; ?>" class="action-btn" title="Podsetnici">
                                <i class="fas fa-bell"></i>
                                <span>Podsetnici</span>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <footer class="modern-footer">
        <div class="container text-center">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Božidar AutoApp • Sva prava zadržana</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/dashboard.js"></script>
</body>
</html>