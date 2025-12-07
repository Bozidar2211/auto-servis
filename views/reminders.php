<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../models/Reminder.php';

// Get user's reminders for selected car
// 1) Učitaj car_id iz URL-a ako postoji, i upiši u sesiju
if (isset($_GET['car_id']) && ctype_digit($_GET['car_id'])) {
    $_SESSION['carid'] = (int)$_GET['car_id'];
}

// 2) Odredi izvor car_id
$carId = $_SESSION['carid'] ?? null;

if ($carId) {
    // Reminders za izabrani auto
    $reminders = Reminder::getAllByCar($carId);
} else {
    // Fallback: svi podsetnici za korisnika
    $reminders = Reminder::getUpcomingByUser($_SESSION['user']['id']);
}


?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Podsećanja | Auto Servis</title>
    
    <!-- Favicon -->
    <link rel="icon" href="../assets/img/favicon.png" type="image/png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/reminders.css">
</head>
<body>

<!-- Animated Background -->
<div class="animated-bg">
    <div class="carbon-fiber"></div>
    <div class="gradient-overlay"></div>
</div>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="../index.php">
            <i class="fas fa-car-side"></i>
            Auto Servis
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">
                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                </li>
               <a class="nav-link" href="add_reminder.php?car_id=<?php echo htmlspecialchars($carId); ?>">
                    <i class="fa-regular fa-bell me-1"></i> Novi Podsetnik
                </a>
                <li class="nav-item">
                    <span class="user-badge">
                        <i class="fas fa-user"></i>
                        <?php echo htmlspecialchars($_SESSION['user']['username']); ?>
                    </span>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-bell"></i>
            </div>
            <h1 class="header-title">Podsetnici</h1>
            <p class="header-subtitle">Upravljajte podacima o servisima i održavanju vašeg automobila</p>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="content-section">
    <div class="container">
        
        

        <!-- Search and Filter Section -->
        <div class="search-filter-section fade-in" style="animation-delay: 0.1s;">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input 
                    type="text" 
                    id="searchInput" 
                    class="search-input" 
                    placeholder="Pretraži podsetnike po vozilu, tipu ili opisu..."
                >
                <button id="clearSearch" class="clear-search-btn">
                    <i class="fas fa-times"></i>
                </button>
            </div>

        </div>

        <!-- Reminders Grid -->
        <div id="remindersGrid" class="reminders-grid">
            <?php foreach ($reminders as $reminder): 
                $Date = new DateTime($reminder['reminder_date']);
                $today = new DateTime();
                $interval = $today->diff($Date);
                $daysUntil = $interval->invert ? -$interval->days : $interval->days;
                
                // Determine reminder type badge
                $typeLabels = [
                    'maintenance' => 'Održavanje',
                    'inspection' => 'Pregled',
                    'service' => 'Servis',
                    'repair' => 'Popravka',
                    'other' => 'Ostalo'
                ];
            ?>
                <div class="reminder-card fade-in" data-id="<?php echo $reminder['id']; ?>"; ?>
                    
                    <!-- Card Header with Status -->
                    <div class="card-header">
                        <h3 class="reminder-title"><?php echo htmlspecialchars($reminder['note']); ?></h3>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="info-item">
                            <span class="info-label">
                                <i class="fas fa-car"></i> Vozilo
                            </span>
                            <span class="info-value">
                                <?php 
                                    require_once __DIR__ . '/../models/Car.php';
                                    $car = Car::getById($reminder['car_id']);
                                    echo htmlspecialchars($car['brand'] . ' ' . $car['model']);
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Empty State -->
        <?php if (empty($reminders)): ?>
            <div class="empty-state fade-in">
                <div class="empty-icon">
                    <i class="fas fa-bell-slash"></i>
                </div>
                <h3>Nema Podsećanja</h3>
                <p>Još niste kreirali nijedan podsetnik. Kreirajte novo podsećanje da biste počeli.</p>
                <a href="add_reminder.php" class="btn-create-reminder">
                    <i class="fas fa-plus"></i>
                    Kreiraj Podsećanje
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Footer -->
<footer class="modern-footer">
    <div class="container text-center">
        <p class="mb-0">&copy; <?php echo date('Y'); ?> Božidar AutoApp • Sva prava zadržana</p>
    </div>
</footer>

<!-- Scroll to top button -->
<div class="scroll-top" id="scrollTop">
    <i class="fas fa-arrow-up"></i>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JavaScript -->
<script src="../assets/js/main.js"></script>
<script src="../assets/js/reminders.js"></script>

</body>
</html>