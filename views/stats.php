<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../models/Car.php';
require_once __DIR__ . '/../models/Service.php';
require_once __DIR__ . '/../models/Modification.php';

// Get car ID from URL
$car_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$car_id) {
    header('Location: dashboard.php');
    exit;
}

// Get car details
$car = Car::getById($car_id);
if (!$car || $car['user_id'] != $_SESSION['user']['id']) {
    header('Location: dashboard.php');
    exit;
}

// Get statistics
$services = Service::getByCar($car_id);
$modifications = Modification::getByCar($car_id);

// Calculate service statistics
$serviceStats = [
    'count' => count($services),
    'total_cost' => 0,
    'max_cost' => 0,
    'min_cost' => 0,
    'average_cost' => 0,
    'by_type' => [],
    'by_month' => [],
];

foreach ($services as $service) {
    $cost = floatval($service['cost']);
    $serviceStats['total_cost'] += $cost;
    $serviceStats['max_cost'] = max($serviceStats['max_cost'], $cost);
    $serviceStats['min_cost'] = $serviceStats['min_cost'] === 0 ? $cost : min($serviceStats['min_cost'], $cost);
    
    // Group by type
    $type = $service['service_type'] ?? 'Nepoznato';
    if (!isset($serviceStats['by_type'][$type])) {
        $serviceStats['by_type'][$type] = 0;
    }
    $serviceStats['by_type'][$type]++;
    
    // Group by month
    $month = date('Y-m', strtotime($service['service_date']));
    if (!isset($serviceStats['by_month'][$month])) {
        $serviceStats['by_month'][$month] = 0;
    }
    $serviceStats['by_month'][$month] += $cost;
}

if ($serviceStats['count'] > 0) {
    $serviceStats['average_cost'] = $serviceStats['total_cost'] / $serviceStats['count'];
}

// Calculate modification statistics
$modStats = [
    'count' => count($modifications),
    'total_cost' => 0,
    'max_cost' => 0,
    'min_cost' => 0,
    'average_cost' => 0,
    'by_type' => [],
];

foreach ($modifications as $mod) {
    $cost = floatval($mod['total_cost']);
    $modStats['total_cost'] += $cost;
    $modStats['max_cost'] = max($modStats['max_cost'], $cost);
    $modStats['min_cost'] = $modStats['min_cost'] === 0 ? $cost : min($modStats['min_cost'], $cost);
    
    $type = $mod['modification_type'] ?? 'Nepoznato';
    if (!isset($modStats['by_type'][$type])) {
        $modStats['by_type'][$type] = 0;
    }
    $modStats['by_type'][$type]++;
}

if ($modStats['count'] > 0) {
    $modStats['average_cost'] = $modStats['total_cost'] / $modStats['count'];
}

// Combined statistics
$totalCost = $serviceStats['total_cost'] + $modStats['total_cost'];
$totalCount = $serviceStats['count'] + $modStats['count'];
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistika Vozila | Auto Servis</title>
    
    <!-- Favicon -->
    <link rel="icon" href="../assets/img/favicon.png" type="image/png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/stats.css">
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
                <i class="fas fa-chart-line"></i>
            </div>
            <h1 class="header-title">Statistika Vozila</h1>
            <p class="header-subtitle">
                <?php echo htmlspecialchars($car['brand'] . ' ' . $car['model'] . ' (' . $car['year'] . ')'); ?>
                <span class="registration-badge"><?php echo htmlspecialchars($car['registration']); ?></span>
            </p>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="content-section">
    <div class="container">
        
        <!-- Summary Cards -->
        <div class="stats-summary fade-in">
            <div class="summary-card">
                <div class="card-icon">
                    <i class="fas fa-wrench"></i>
                </div>
                <div class="card-content">
                    <div class="card-label">Ukupno Servisa</div>
                    <div class="card-value"><?php echo $serviceStats['count']; ?></div>
                    <div class="card-subtitle">
                        <i class="fas fa-arrow-trend-up"></i>
                        Redovno održavanje
                    </div>
                </div>
            </div>

            <div class="summary-card">
                <div class="card-icon">
                    <i class="fas fa-hammer"></i>
                </div>
                <div class="card-content">
                    <div class="card-label">Modifikacije</div>
                    <div class="card-value"><?php echo $modStats['count']; ?></div>
                    <div class="card-subtitle">
                        <i class="fas fa-paint-brush"></i>
                        Poboljšanja
                    </div>
                </div>
            </div>

            <div class="summary-card">
                <div class="card-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="card-content">
                    <div class="card-label">Ukupni Trošak</div>
                    <div class="card-value"><?php echo number_format($totalCost, 0); ?></div>
                    <div class="card-subtitle">RSD</div>
                </div>
            </div>

            <div class="summary-card">
                <div class="card-icon">
                    <i class="fas fa-calculator"></i>
                </div>
                <div class="card-content">
                    <div class="card-label">Prosečan Trošak</div>
                    <div class="card-value"><?php echo $totalCount > 0 ? number_format($totalCost / $totalCount, 0) : 0; ?></div>
                    <div class="card-subtitle">Po operaciji</div>
                </div>
            </div>
        </div>

        <!-- Charts Container -->
        <div class="charts-grid fade-in" style="animation-delay: 0.1s;">
            
            <!-- Cost Distribution Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Raspodela Troškova</h3>
                    <span class="chart-badge">Servisi vs Modifikacije</span>
                </div>
                <div class="chart-container">
                    <canvas id="costChart"></canvas>
                </div>
                <div class="chart-legend">
                    <div class="legend-item">
                        <span class="legend-color" style="background: #f0ad4e;"></span>
                        Servisi: <?php echo number_format($serviceStats['total_cost'], 0); ?> RSD
                    </div>
                    <div class="legend-item">
                        <span class="legend-color" style="background: #17a2b8;"></span>
                        Modifikacije: <?php echo number_format($modStats['total_cost'], 0); ?> RSD
                    </div>
                </div>
            </div>

            <!-- Monthly Spending Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Mesečni Troškovi Servisa</h3>
                    <span class="chart-badge">Trends</span>
                </div>
                <div class="chart-container">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Detailed Stats -->
        <div class="detailed-stats fade-in" style="animation-delay: 0.2s;">
            
            <!-- Services Stats -->
            <div class="stats-card">
                <div class="stats-header">
                    <div class="header-icon">
                        <i class="fas fa-wrench"></i>
                    </div>
                    <h3>Detaljno - Servisi</h3>
                </div>

                <?php if ($serviceStats['count'] > 0): ?>
                    <div class="stats-table">
                        <div class="stat-row">
                            <span class="stat-label">Broj servisa:</span>
                            <span class="stat-value"><?php echo $serviceStats['count']; ?></span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Ukupan trošak:</span>
                            <span class="stat-value"><?php echo number_format($serviceStats['total_cost'], 2); ?> RSD</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Prosečan trošak:</span>
                            <span class="stat-value"><?php echo number_format($serviceStats['average_cost'], 2); ?> RSD</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Najskuplji servis:</span>
                            <span class="stat-value highlight-high"><?php echo number_format($serviceStats['max_cost'], 2); ?> RSD</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Najjeftiniji servis:</span>
                            <span class="stat-value highlight-low"><?php echo number_format($serviceStats['min_cost'], 2); ?> RSD</span>
                        </div>
                    </div>

                    <?php if (!empty($serviceStats['by_type'])): ?>
                        <div class="stats-breakdown">
                            <h4>Po tipu servisa:</h4>
                            <div class="type-list">
                                <?php foreach ($serviceStats['by_type'] as $type => $count): ?>
                                    <div class="type-item">
                                        <span class="type-name"><?php echo htmlspecialchars($type); ?></span>
                                        <span class="type-count">
                                            <i class="fas fa-tools"></i>
                                            <?php echo $count; ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="empty-message">
                        <i class="fas fa-inbox"></i>
                        <p>Nema podataka o servisima za ovo vozilo</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Modifications Stats -->
            <div class="stats-card">
                <div class="stats-header">
                    <div class="header-icon">
                        <i class="fas fa-hammer"></i>
                    </div>
                    <h3>Detaljno - Modifikacije</h3>
                </div>

                <?php if ($modStats['count'] > 0): ?>
                    <div class="stats-table">
                        <div class="stat-row">
                            <span class="stat-label">Broj modifikacija:</span>
                            <span class="stat-value"><?php echo $modStats['count']; ?></span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Ukupan trošak:</span>
                            <span class="stat-value"><?php echo number_format($modStats['total_cost'], 2); ?> RSD</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Prosečan trošak:</span>
                            <span class="stat-value"><?php echo number_format($modStats['average_cost'], 2); ?> RSD</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Najskuplja modifikacija:</span>
                            <span class="stat-value highlight-high"><?php echo number_format($modStats['max_cost'], 2); ?> RSD</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Najjeftinija modifikacija:</span>
                            <span class="stat-value highlight-low"><?php echo number_format($modStats['min_cost'], 2); ?> RSD</span>
                        </div>
                    </div>

                    <?php if (!empty($modStats['by_type'])): ?>
                        <div class="stats-breakdown">
                            <h4>Po tipu modifikacije:</h4>
                            <div class="type-list">
                                <?php foreach ($modStats['by_type'] as $type => $count): ?>
                                    <div class="type-item">
                                        <span class="type-name"><?php echo htmlspecialchars($type); ?></span>
                                        <span class="type-count">
                                            <i class="fas fa-paint-brush"></i>
                                            <?php echo $count; ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="empty-message">
                        <i class="fas fa-inbox"></i>
                        <p>Nema podataka o modifikacijama za ovo vozilo</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons fade-in" style="animation-delay: 0.3s;">
            <a href="services.php?car_id=<?php echo $car_id; ?>" class="btn-action btn-primary">
                <i class="fas fa-wrench"></i>
                Servisi
            </a>
            <a href="modifications.php?car_id=<?php echo $car_id; ?>" class="btn-action btn-info">
                <i class="fas fa-hammer"></i>
                Modifikacije
            </a>
            <a href="dashboard.php" class="btn-action btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Nazad na Dashboard
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
<script src="../assets/js/stats.js"></script>

<!-- Chart Data -->
<script>
    const chartData = {
        costDistribution: {
            services: <?php echo $serviceStats['total_cost']; ?>,
            modifications: <?php echo $modStats['total_cost']; ?>
        },
        monthlySpending: {
            labels: <?php echo json_encode(array_keys($serviceStats['by_month'])); ?>,
            values: <?php echo json_encode(array_values($serviceStats['by_month'])); ?>
        }
    };
</script>

</body>
</html>
