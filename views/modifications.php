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

// Try both method names
if (method_exists('Modification', 'getByCarId')) {
    $modifications = Modification::getByCarId($carId);
} elseif (method_exists('Modification', 'getByCar')) {
    $modifications = Modification::getByCar($carId);
} else {
    $modifications = [];
}

// Calculate total cost
$totalCost = 0;
$completedMods = 0;
$plannedMods = 0;

foreach ($modifications as $mod) {
    // Handle both old and new column names
    $modCost = $mod['total_cost'] ?? ($mod['cost'] ?? 0);
    $totalCost += $modCost;
    
    if (isset($mod['status'])) {
        if ($mod['status'] === 'Završena') {
            $completedMods++;
        } elseif ($mod['status'] === 'Planirana') {
            $plannedMods++;
        }
    }
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
                    <a class="nav-link" href="dashboard.php">
                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                </li>
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
                <i class="fas fa-tools"></i>
            </div>
            <h1 class="header-title">Modifikacije Vozila</h1>
            <p class="header-subtitle">
                <?php 
                    // Handle both old and new column names
                    $carBrand = $car['make'] ?? $car['brand'] ?? 'Nepoznat';
                    $carModel = $car['model'] ?? '';
                    $carYear = isset($car['year']) ? ' (' . $car['year'] . ')' : '';
                    echo htmlspecialchars($carBrand . ' ' . $carModel . $carYear); 
                ?>
            </p>
        </div>
    </div>
</section>

<!-- Success/Error Messages -->
<?php if (isset($_SESSION['success_message'])): ?>
<div class="container mt-4">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i>
        <?= $_SESSION['success_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php unset($_SESSION['success_message']); endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
<div class="container mt-4">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i>
        <?= $_SESSION['error_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php unset($_SESSION['error_message']); endif; ?>

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
                                $lastMod = reset($modifications); // First one (newest)
                                $lastDate = $lastMod['installation_date'] ?? $lastMod['mod_date'] ?? null;
                                if ($lastDate) {
                                    echo date('d.m.Y', strtotime($lastDate));
                                } else {
                                    echo '--';
                                }
                            } else {
                                echo '--';
                            }
                        ?>
                    </div>
                    <div class="stat-label">Poslednja Modifikacija</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo $completedMods; ?></div>
                    <div class="stat-label">Završeno</div>
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
                    <?php foreach ($modifications as $mod): 
                        // Handle both old and new column names
                        $modDate = $mod['installation_date'] ?? $mod['mod_date'] ?? date('Y-m-d');
                        $modCost = $mod['total_cost'] ?? $mod['cost'] ?? 0;
                        $modType = $mod['mod_type'] ?? 'Modifikacija';
                        $modDescription = $mod['description'] ?? '';
                        $modCategory = $mod['category'] ?? null;
                        $modStatus = $mod['status'] ?? null;
                        $modInstallationCost = $mod['installation_cost'] ?? null;
                        $modPartsCost = $mod['parts_cost'] ?? null;
                        $modWarranty = $mod['warranty'] ?? null;
                        
                        // Status badge color
                        $statusClass = '';
                        $statusIcon = '';
                        if ($modStatus === 'Završena') {
                            $statusClass = 'badge-success';
                            $statusIcon = 'fa-check-circle';
                        } elseif ($modStatus === 'U toku') {
                            $statusClass = 'badge-warning';
                            $statusIcon = 'fa-spinner';
                        } elseif ($modStatus === 'Planirana') {
                            $statusClass = 'badge-info';
                            $statusIcon = 'fa-clock';
                        }
                    ?>
                        <div class="modification-card" data-date="<?php echo $modDate; ?>" data-cost="<?php echo $modCost; ?>">
                            <div class="card-header">
                                <div class="card-title">
                                    <i class="fas fa-wrench"></i>
                                    <span><?php echo htmlspecialchars($modType); ?></span>
                                </div>
                                <div class="card-badges">
                                    <?php if ($modStatus): ?>
                                        <span class="status-badge <?php echo $statusClass; ?>">
                                            <i class="fas <?php echo $statusIcon; ?>"></i>
                                            <?php echo htmlspecialchars($modStatus); ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($modCategory): ?>
                                        <span class="category-badge">
                                            <i class="fas fa-tag"></i>
                                            <?php echo htmlspecialchars($modCategory); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="card-body">
                                <?php if ($modDescription): ?>
                                    <p class="card-description"><?php echo nl2br(htmlspecialchars($modDescription)); ?></p>
                                <?php endif; ?>
                                
                                <div class="card-meta">
                                    <div class="meta-row">
                                        <div class="meta-item">
                                            <span class="meta-label">
                                                <i class="fas fa-calendar"></i> Datum:
                                            </span>
                                            <span class="meta-value">
                                                <?php echo date('d.m.Y', strtotime($modDate)); ?>
                                            </span>
                                        </div>
                                        
                                        <?php if ($modWarranty): ?>
                                        <div class="meta-item">
                                            <span class="meta-label">
                                                <i class="fas fa-shield-alt"></i> Garantija:
                                            </span>
                                            <span class="meta-value">
                                                <?php echo $modWarranty; ?> meseci
                                            </span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="cost-breakdown">
                                        <?php if ($modInstallationCost !== null && $modInstallationCost > 0): ?>
                                        <div class="cost-item">
                                            <span class="cost-label">
                                                <i class="fas fa-wrench"></i> Instalacija:
                                            </span>
                                            <span class="cost-value">
                                                <?php echo number_format($modInstallationCost, 2, ',', '.'); ?> RSD
                                            </span>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($modPartsCost !== null && $modPartsCost > 0): ?>
                                        <div class="cost-item">
                                            <span class="cost-label">
                                                <i class="fas fa-box"></i> Delovi:
                                            </span>
                                            <span class="cost-value">
                                                <?php echo number_format($modPartsCost, 2, ',', '.'); ?> RSD
                                            </span>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <div class="cost-item total-cost">
                                            <span class="cost-label">
                                                <i class="fas fa-calculator"></i> Ukupno:
                                            </span>
                                            <span class="cost-value cost-badge">
                                                <?php echo number_format($modCost, 2, ',', '.'); ?> RSD
                                            </span>
                                        </div>
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

<script>
function confirmDelete(event) {
    return confirm('Da li ste sigurni da želite da obrišete ovu modifikaciju?');
}
</script>

</body>
</html>