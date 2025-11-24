<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../models/Reminder.php';

// Get user's reminders
$reminders = Reminder::getByUser($_SESSION['user']['id']);

// Get stats
$stats = [
    'active' => count(array_filter($reminders, fn($r) => $r['status'] === 'active')),
    'completed' => count(array_filter($reminders, fn($r) => $r['status'] === 'completed')),
    'overdue' => count(array_filter($reminders, fn($r) => $r['status'] === 'active' && strtotime($r['due_date']) < time())),
    'total' => count($reminders)
];
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
                    <a class="nav-link" href="dashboard.php">Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add_reminder.php">Novo Podsećanje</a>
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
            <h1 class="header-title">Podsećanja</h1>
            <p class="header-subtitle">Upravljajte podacima o servisima i održavanju vašeg automobila</p>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="content-section">
    <div class="container">
        
        <!-- Stats Section -->
        <div class="stats-grid fade-in">
            <div class="stat-card">
                <div class="stat-icon active">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Aktivna</div>
                    <div class="stat-number" id="activeCount"><?php echo $stats['active']; ?></div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon completed">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Završena</div>
                    <div class="stat-number" id="completedCount"><?php echo $stats['completed']; ?></div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon overdue">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Prekoračena</div>
                    <div class="stat-number" id="overdueCount"><?php echo $stats['overdue']; ?></div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-list"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Ukupno</div>
                    <div class="stat-number" id="totalCount"><?php echo $stats['total']; ?></div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-filter-section fade-in" style="animation-delay: 0.1s;">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input 
                    type="text" 
                    id="searchInput" 
                    class="search-input" 
                    placeholder="Pretraži podsećanja po vozilu, tipu ili opisu..."
                >
                <button id="clearSearch" class="clear-search-btn">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">
                    <i class="fas fa-list"></i> Sva (<?php echo $stats['total']; ?>)
                </button>
                <button class="filter-btn" data-filter="active">
                    <i class="fas fa-clock"></i> Aktivna (<?php echo $stats['active']; ?>)
                </button>
                <button class="filter-btn" data-filter="completed">
                    <i class="fas fa-check-circle"></i> Završena (<?php echo $stats['completed']; ?>)
                </button>
                <button class="filter-btn" data-filter="overdue">
                    <i class="fas fa-exclamation-circle"></i> Prekoračena (<?php echo $stats['overdue']; ?>)
                </button>
            </div>
        </div>

        <!-- Reminders Grid -->
        <div id="remindersGrid" class="reminders-grid">
            <?php foreach ($reminders as $reminder): 
                $dueDate = new DateTime($reminder['due_date']);
                $today = new DateTime();
                $interval = $today->diff($dueDate);
                $isOverdue = $today > $dueDate && $reminder['status'] === 'active';
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
                <div class="reminder-card fade-in" data-id="<?php echo $reminder['id']; ?>" data-status="<?php echo $reminder['status']; ?>" data-overdue="<?php echo $isOverdue ? 'true' : 'false'; ?>">
                    
                    <!-- Card Header with Status -->
                    <div class="card-header">
                        <div class="header-top">
                            <div class="reminder-type">
                                <span class="type-badge type-<?php echo $reminder['type']; ?>">
                                    <?php echo htmlspecialchars($typeLabels[$reminder['type']] ?? 'Ostalo'); ?>
                                </span>
                                <?php if ($isOverdue): ?>
                                    <span class="overdue-badge">
                                        <i class="fas fa-exclamation-triangle"></i> Prekoračeno
                                    </span>
                                <?php endif; ?>
                            </div>
                            <button class="card-menu-btn" data-id="<?php echo $reminder['id']; ?>">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                        
                        <h3 class="reminder-title"><?php echo htmlspecialchars($reminder['title']); ?></h3>
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

                        <div class="info-item">
                            <span class="info-label">
                                <i class="fas fa-calendar"></i> Rok
                            </span>
                            <span class="info-value due-date <?php echo $isOverdue ? 'overdue' : ''; ?>">
                                <?php echo $dueDate->format('d.m.Y'); ?>
                                <span class="days-info <?php echo $isOverdue ? 'danger' : 'success'; ?>">
                                    (<?php echo $isOverdue ? '-' . abs($daysUntil) . ' dana' : '+' . $daysUntil . ' dana'; ?>)
                                </span>
                            </span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">
                                <i class="fas fa-file-alt"></i> Opis
                            </span>
                            <span class="info-value description-preview">
                                <?php echo htmlspecialchars(substr($reminder['description'], 0, 80)) . (strlen($reminder['description']) > 80 ? '...' : ''); ?>
                            </span>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="progress-section">
                        <div class="progress-label">
                            <span>Napredak</span>
                            <span class="progress-percent"><?php echo htmlspecialchars($reminder['progress'] ?? 0); ?>%</span>
                        </div>
                        <div class="progress-bar-wrapper">
                            <div class="progress-bar" style="width: <?php echo htmlspecialchars($reminder['progress'] ?? 0); ?>%"></div>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="card-footer">
                        <div class="footer-left">
                            <span class="status-indicator" data-status="<?php echo $reminder['status']; ?>">
                                <?php if ($reminder['status'] === 'active'): ?>
                                    <i class="fas fa-circle"></i> Aktivno
                                <?php else: ?>
                                    <i class="fas fa-check"></i> Završeno
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="footer-right">
                            <button class="btn-view-details" data-id="<?php echo $reminder['id']; ?>">
                                <i class="fas fa-arrow-right"></i>
                                <span>Detalji</span>
                            </button>
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

<!-- Reminder Details Modal -->
<div id="reminderModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Detalji Podsećanja</h2>
            <button id="closeModal" class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="modalBody">
            <!-- Content loaded dynamically -->
        </div>
    </div>
</div>

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