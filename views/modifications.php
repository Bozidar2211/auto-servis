<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../controllers/ModificationController.php';

$carId = $_GET['car_id'] ?? null;

if (!$carId) {
    header('Location: dashboard.php');
    exit;
}

// Get car details
require_once __DIR__ . '/../models/Car.php';
$car = Car::getById($carId);

if (!$car || $car['user_id'] != $_SESSION['user']['id']) {
    header('Location: dashboard.php');
    exit;
}

// Get modifications for this car
require_once __DIR__ . '/../models/Modification.php';
$modifications = Modification::getByCar($carId);

// Calculate total cost
$totalCost = 0;
foreach ($modifications as $mod) {
    $totalCost += $mod['cost'];
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifikacije vozila | Auto Servis</title>
    
    <!-- Favicon -->
    <link rel="icon" href="../assets/img/favicon.png" type="image/png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/modifications.css">
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
                    <a class="nav-link" href="#">Modifikacije</a>
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
                <i class="fas fa-tools"></i>
            </div>
            <h1 class="header-title">Modifikacije Vozila</h1>
            <p class="header-subtitle"><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></p>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="content-section">
    <div class="container">
        
        <!-- Stats Cards -->
        <div class="stats-grid fade-in">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-hammer"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo count($modifications); ?></div>
                    <div class="stat-label">Ukupno Modifikacija</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-money-bill"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo number_format($totalCost, 0, ',', '.'); ?></div>
                    <div class="stat-label">Ukupna Vrednost (RSD)</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">
                        <?php 
                            if (!empty($modifications)) {
                                $lastMod = end($modifications);
                                echo date('d.m.Y', strtotime($lastMod['mod_date']));
                            } else {
                                echo '--';
                            }
                        ?>
                    </div>
                    <div class="stat-label">Poslednja Modifikacija</div>
                </div>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="filter-section fade-in" style="animation-delay: 0.1s;">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input 
                    type="text" 
                    id="searchInput" 
                    class="search-input" 
                    placeholder="Pretraži modifikacije..."
                >
            </div>
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">
                    <i class="fas fa-list"></i> Sve
                </button>
                <button class="filter-btn" data-filter="recent">
                    <i class="fas fa-clock"></i> Skoro
                </button>
                <button class="filter-btn" data-filter="expensive">
                    <i class="fas fa-money-bill"></i> Skupo
                </button>
            </div>
        </div>

        <!-- Modifications List -->
        <div class="modifications-container fade-in" style="animation-delay: 0.2s;">
            
            <?php if (empty($modifications)): ?>
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h3>Nema Evidentiranih Modifikacija</h3>
                    <p>Još niste evidentirali nijednu modifikaciju za ovo vozilo.</p>
                    <a href="add_modification.php?car_id=<?php echo htmlspecialchars($carId); ?>" class="btn-primary-action">
                        <i class="fas fa-plus"></i>
                        Dodaj Prvu Modifikaciju
                    </a>
                </div>
            <?php else: ?>
                <!-- Modifications List -->
                <div class="modifications-list">
                    <?php foreach ($modifications as $mod): ?>
                        <div class="modification-card" data-date="<?php echo $mod['mod_date']; ?>" data-cost="<?php echo $mod['cost']; ?>">
                            <div class="card-header">
                                <div class="card-title">
                                    <i class="fas fa-wrench"></i>
                                    <span><?php echo htmlspecialchars(substr($mod['description'], 0, 50)) . (strlen($mod['description']) > 50 ? '...' : ''); ?></span>
                                </div>
                                <div class="card-date">
                                    <i class="fas fa-calendar"></i>
                                    <?php echo date('d.m.Y', strtotime($mod['mod_date'])); ?>
                                </div>
                            </div>

                            <div class="card-body">
                                <p class="card-description"><?php echo htmlspecialchars($mod['description']); ?></p>
                                
                                <div class="card-meta">
                                    <div class="meta-item">
                                        <span class="meta-label">Cena:</span>
                                        <span class="meta-value cost-badge">
                                            <?php echo number_format($mod['cost'], 2, ',', '.'); ?> RSD
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="card-actions">
                                <a href="edit_modification.php?id=<?php echo $mod['id']; ?>" class="action-btn edit-btn">
                                    <i class="fas fa-edit"></i>
                                    <span>Izmeni</span>
                                </a>
                                <form method="POST" action="../controllers/DeleteController.php" class="action-form" onsubmit="return confirmDelete(event);">
                                    <input type="hidden" name="type" value="modification">
                                    <input type="hidden" name="id" value="<?php echo $mod['id']; ?>">
                                    <input type="hidden" name="car_id" value="<?php echo htmlspecialchars($carId); ?>">
                                    <button type="submit" class="action-btn delete-btn">
                                        <i class="fas fa-trash-alt"></i>
                                        <span>Obriši</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>

        <!-- Action Buttons -->
        <div class="action-buttons fade-in" style="animation-delay: 0.3s;">
            <a href="add_modification.php?car_id=<?php echo htmlspecialchars($carId); ?>" class="btn-primary-action">
                <i class="fas fa-plus"></i>
                <span>Dodaj Modifikaciju</span>
            </a>
            <a href="dashboard.php" class="btn-secondary-action">
                <i class="fas fa-arrow-left"></i>
                <span>Nazad na Vozila</span>
            </a>
        </div>

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
<script src="../assets/js/modifications.js"></script>

</body>
</html>