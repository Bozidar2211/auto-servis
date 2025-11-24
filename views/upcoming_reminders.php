<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../models/Reminder.php';

$reminders = Reminder::getUpcomingByUser($_SESSION['user']['id']);
$today = date('Y-m-d');
$weekAhead = date('Y-m-d', strtotime('+7 days'));

// Kategorisuj podseetnike
$todayReminders = [];
$soonReminders = [];
$missedReminders = [];
$laterReminders = [];

foreach ($reminders as $reminder) {
    $date = $reminder['reminder_date'];
    if ($date === $today) {
        $todayReminders[] = $reminder;
    } elseif ($date > $today && $date <= $weekAhead) {
        $soonReminders[] = $reminder;
    } elseif ($date < $today) {
        $missedReminders[] = $reminder;
    } else {
        $laterReminders[] = $reminder;
    }
}

$stats = [
    'today' => count($todayReminders),
    'soon' => count($soonReminders),
    'missed' => count($missedReminders),
    'later' => count($laterReminders),
    'total' => count($reminders)
];
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nadolazeći Podsetnici | Auto Servis</title>
    
    <!-- Favicon -->
    <link rel="icon" href="../assets/img/favicon.png" type="image/png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/upcoming_reminders.css">
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
                    <a class="nav-link" href="dashboard.php">Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reminders.php">Svi Podsetnici</a>
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
                <i class="fas fa-calendar-alt"></i>
            </div>
            <h1 class="header-title">Nadolazeći Podsetnici</h1>
            <p class="header-subtitle">Pratite važne datume za održavanje vaših automobila</p>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="content-section">
    <div class="container">
        
        <!-- Timeline Stats -->
        <div class="timeline-stats fade-in">
            <div class="timeline-stat-card today">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Danas</div>
                    <div class="stat-number" id="todayCount"><?php echo $stats['today']; ?></div>
                </div>
            </div>
            
            <div class="timeline-stat-card soon">
                <div class="stat-icon">
                    <i class="fas fa-exclamation"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Uskoro (7 dana)</div>
                    <div class="stat-number" id="soonCount"><?php echo $stats['soon']; ?></div>
                </div>
            </div>
            
            <div class="timeline-stat-card missed">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Propušteno</div>
                    <div class="stat-number" id="missedCount"><?php echo $stats['missed']; ?></div>
                </div>
            </div>
            
            <div class="timeline-stat-card later">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Kasnije</div>
                    <div class="stat-number" id="laterCount"><?php echo $stats['later']; ?></div>
                </div>
            </div>
        </div>

        <!-- Timeline View -->
        <div class="timeline-container fade-in" style="animation-delay: 0.1s;">
            
            <!-- Today Section -->
            <?php if (!empty($todayReminders)): ?>
                <div class="timeline-section">
                    <div class="timeline-header today">
                        <div class="header-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h2 class="section-title">Danas</h2>
                        <span class="count-badge"><?php echo count($todayReminders); ?></span>
                    </div>
                    
                    <div class="reminders-list">
                        <?php foreach ($todayReminders as $reminder): 
                            require_once __DIR__ . '/../models/Car.php';
                            $car = Car::getById($reminder['car_id']);
                        ?>
                            <div class="reminder-item today-item fade-in">
                                <div class="item-left">
                                    <div class="timeline-dot today"></div>
                                </div>
                                
                                <div class="item-content">
                                    <div class="item-header">
                                        <h3 class="item-title"><?php echo htmlspecialchars($reminder['title']); ?></h3>
                                        <span class="priority-badge priority-<?php echo $reminder['priority'] ?? 'medium'; ?>">
                                            <?php echo ucfirst($reminder['priority'] ?? 'medium'); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="item-meta">
                                        <span class="meta-item">
                                            <i class="fas fa-car"></i>
                                            <?php echo htmlspecialchars($car['brand'] . ' ' . $car['model'] . ' (' . $car['year'] . ')'); ?>
                                        </span>
                                        <span class="meta-item">
                                            <i class="fas fa-calendar"></i>
                                            <?php echo date('d.m.Y', strtotime($reminder['reminder_date'])); ?>
                                        </span>
                                    </div>
                                    
                                    <p class="item-description">
                                        <?php echo htmlspecialchars(substr($reminder['description'], 0, 100)) . (strlen($reminder['description']) > 100 ? '...' : ''); ?>
                                    </p>
                                </div>
                                
                                <div class="item-actions">
                                    <a href="edit_reminder.php?id=<?php echo $reminder['id']; ?>" class="btn-action edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn-action delete" data-id="<?php echo $reminder['id']; ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Soon Section -->
            <?php if (!empty($soonReminders)): ?>
                <div class="timeline-section">
                    <div class="timeline-header soon">
                        <div class="header-icon">
                            <i class="fas fa-exclamation"></i>
                        </div>
                        <h2 class="section-title">Uskoro (7 Dana)</h2>
                        <span class="count-badge"><?php echo count($soonReminders); ?></span>
                    </div>
                    
                    <div class="reminders-list">
                        <?php foreach ($soonReminders as $reminder): 
                            require_once __DIR__ . '/../models/Car.php';
                            $car = Car::getById($reminder['car_id']);
                        ?>
                            <div class="reminder-item soon-item fade-in">
                                <div class="item-left">
                                    <div class="timeline-dot soon"></div>
                                </div>
                                
                                <div class="item-content">
                                    <div class="item-header">
                                        <h3 class="item-title"><?php echo htmlspecialchars($reminder['title']); ?></h3>
                                        <span class="priority-badge priority-<?php echo $reminder['priority'] ?? 'medium'; ?>">
                                            <?php echo ucfirst($reminder['priority'] ?? 'medium'); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="item-meta">
                                        <span class="meta-item">
                                            <i class="fas fa-car"></i>
                                            <?php echo htmlspecialchars($car['brand'] . ' ' . $car['model'] . ' (' . $car['year'] . ')'); ?>
                                        </span>
                                        <span class="meta-item">
                                            <i class="fas fa-calendar"></i>
                                            <?php echo date('d.m.Y', strtotime($reminder['reminder_date'])); ?>
                                        </span>
                                        <span class="meta-item days-left">
                                            <i class="fas fa-hourglass-end"></i>
                                            <span data-date="<?php echo $reminder['reminder_date']; ?>">--</span> dana
                                        </span>
                                    </div>
                                    
                                    <p class="item-description">
                                        <?php echo htmlspecialchars(substr($reminder['description'], 0, 100)) . (strlen($reminder['description']) > 100 ? '...' : ''); ?>
                                    </p>
                                </div>
                                
                                <div class="item-actions">
                                    <a href="edit_reminder.php?id=<?php echo $reminder['id']; ?>" class="btn-action edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn-action delete" data-id="<?php echo $reminder['id']; ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Missed Section -->
            <?php if (!empty($missedReminders)): ?>
                <div class="timeline-section">
                    <div class="timeline-header missed">
                        <div class="header-icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <h2 class="section-title">Propušteno</h2>
                        <span class="count-badge"><?php echo count($missedReminders); ?></span>
                    </div>
                    
                    <div class="reminders-list">
                        <?php foreach ($missedReminders as $reminder): 
                            require_once __DIR__ . '/../models/Car.php';
                            $car = Car::getById($reminder['car_id']);
                        ?>
                            <div class="reminder-item missed-item fade-in">
                                <div class="item-left">
                                    <div class="timeline-dot missed"></div>
                                </div>
                                
                                <div class="item-content">
                                    <div class="item-header">
                                        <h3 class="item-title"><?php echo htmlspecialchars($reminder['title']); ?></h3>
                                        <span class="priority-badge priority-<?php echo $reminder['priority'] ?? 'medium'; ?>">
                                            <?php echo ucfirst($reminder['priority'] ?? 'medium'); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="item-meta">
                                        <span class="meta-item">
                                            <i class="fas fa-car"></i>
                                            <?php echo htmlspecialchars($car['brand'] . ' ' . $car['model'] . ' (' . $car['year'] . ')'); ?>
                                        </span>
                                        <span class="meta-item">
                                            <i class="fas fa-calendar"></i>
                                            <?php echo date('d.m.Y', strtotime($reminder['reminder_date'])); ?>
                                        </span>
                                        <span class="meta-item overdue">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Propušteno je
                                        </span>
                                    </div>
                                    
                                    <p class="item-description">
                                        <?php echo htmlspecialchars(substr($reminder['description'], 0, 100)) . (strlen($reminder['description']) > 100 ? '...' : ''); ?>
                                    </p>
                                </div>
                                
                                <div class="item-actions">
                                    <a href="edit_reminder.php?id=<?php echo $reminder['id']; ?>" class="btn-action edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn-action delete" data-id="<?php echo $reminder['id']; ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Later Section -->
            <?php if (!empty($laterReminders)): ?>
                <div class="timeline-section">
                    <div class="timeline-header later">
                        <div class="header-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h2 class="section-title">Kasnije</h2>
                        <span class="count-badge"><?php echo count($laterReminders); ?></span>
                    </div>
                    
                    <div class="reminders-list">
                        <?php foreach ($laterReminders as $reminder): 
                            require_once __DIR__ . '/../models/Car.php';
                            $car = Car::getById($reminder['car_id']);
                        ?>
                            <div class="reminder-item later-item fade-in">
                                <div class="item-left">
                                    <div class="timeline-dot later"></div>
                                </div>
                                
                                <div class="item-content">
                                    <div class="item-header">
                                        <h3 class="item-title"><?php echo htmlspecialchars($reminder['title']); ?></h3>
                                        <span class="priority-badge priority-<?php echo $reminder['priority'] ?? 'medium'; ?>">
                                            <?php echo ucfirst($reminder['priority'] ?? 'medium'); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="item-meta">
                                        <span class="meta-item">
                                            <i class="fas fa-car"></i>
                                            <?php echo htmlspecialchars($car['brand'] . ' ' . $car['model'] . ' (' . $car['year'] . ')'); ?>
                                        </span>
                                        <span class="meta-item">
                                            <i class="fas fa-calendar"></i>
                                            <?php echo date('d.m.Y', strtotime($reminder['reminder_date'])); ?>
                                        </span>
                                    </div>
                                    
                                    <p class="item-description">
                                        <?php echo htmlspecialchars(substr($reminder['description'], 0, 100)) . (strlen($reminder['description']) > 100 ? '...' : ''); ?>
                                    </p>
                                </div>
                                
                                <div class="item-actions">
                                    <a href="edit_reminder.php?id=<?php echo $reminder['id']; ?>" class="btn-action edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn-action delete" data-id="<?php echo $reminder['id']; ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Empty State -->
        <?php if (empty($reminders)): ?>
            <div class="empty-state fade-in">
                <div class="empty-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <h3>Nema Podsetnika</h3>
                <p>Nemate nadolazećih podsetnika. Kreirajte novi podsetnik da biste pratili održavanje vaših automobila.</p>
                <a href="reminders.php" class="btn-create-reminder">
                    <i class="fas fa-plus"></i>
                    Kreiraj Podsetnik
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
<script src="../assets/js/upcoming_reminders.js"></script>

</body>
</html>