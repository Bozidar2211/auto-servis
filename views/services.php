<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../controllers/ServiceController.php';

// Dohvati podatke o vozilu za header
require_once __DIR__ . '/../models/Car.php';
$car = null;
if ($carId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
    $stmt->execute([$carId]);
    $car = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servisna Istorija | Auto Servis</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/services.css">
</head>
<body>

    <!-- Animated Background -->
    <div class="animated-bg">
        <div class="carbon-fiber"></div>
        <div class="gradient-overlay"></div>
    </div>

    <!-- Header -->
    <header class="services-header">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="brand-section">
                    <i class="fas fa-wrench brand-icon"></i>
                    <div>
                        <h1 class="brand-title mb-0">Servisna Istorija</h1>
                        <?php if ($car): ?>
                            <p class="brand-subtitle mb-0">
                                <?php echo htmlspecialchars($car['brand'] . ' ' . $car['model'] . ' (' . $car['year'] . ')'); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="user-section">
                    <div class="user-info">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="user-details">
                            <span class="user-name"><?php echo htmlspecialchars($_SESSION['user']['username']); ?></span>
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
        <!-- Stats Cards -->
        <?php 
        $totalCost = array_sum(array_column($services, 'cost'));
        $avgCost = count($services) > 0 ? $totalCost / count($services) : 0;
        $lastService = !empty($services) ? $services[0]['service_date'] : 'N/A';
        ?>
        
        <div class="stats-grid fade-in">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-list-check"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Ukupno Servisa</span>
                    <span class="stat-number" data-count="<?php echo count($services); ?>"><?php echo count($services); ?></span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Ukupan Trošak</span>
                    <span class="stat-number"><?php echo number_format($totalCost, 0); ?> RSD</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calculator"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Prosečan Trošak</span>
                    <span class="stat-number"><?php echo number_format($avgCost, 0); ?> RSD</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Poslednji Servis</span>
                    <span class="stat-number"><?php echo $lastService; ?></span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons-section fade-in" style="animation-delay: 0.1s;">
            <a href="add_service.php?car_id=<?php echo $carId; ?>" class="action-btn action-btn-add">
                <i class="fas fa-plus-circle me-2"></i>Dodaj Servis
            </a>
            <a href="dashboard.php" class="action-btn action-btn-back">
                <i class="fas fa-arrow-left me-2"></i>Nazad
            </a>
        </div>

        <!-- Services Section -->
        <div class="services-section fade-in" style="animation-delay: 0.2s;">
            <div class="section-header">
                <h3><i class="fas fa-history me-2"></i>Istorija Servisa</h3>
                <div class="search-filter">
                    <input type="text" 
                           id="searchServices" 
                           class="search-input" 
                           placeholder="Pretraži servise...">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </div>

            <?php if (empty($services)): ?>
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="fas fa-wrench"></i>
                    <h4>Nema Evidentiranih Servisa</h4>
                    <p>Dodajte prvi servis za ovo vozilo kako biste pratili istoriju održavanja</p>
                    <a href="add_service.php?car_id=<?php echo $carId; ?>" class="btn-empty-action">
                        Dodaj Prvi Servis
                    </a>
                </div>
            <?php else: ?>
                <!-- Services Grid -->
                <div class="services-grid">
                    <?php foreach ($services as $index => $service): ?>
                        <div class="service-card" style="animation-delay: <?php echo 0.3 + ($index * 0.05); ?>s;" data-service-id="<?php echo $service['id']; ?>">
                            <!-- Card Header -->
                            <div class="service-header">
                                <div class="service-date">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span><?php echo date('d.m.Y', strtotime($service['service_date'])); ?></span>
                                </div>
                                <div class="service-cost">
                                    <?php echo number_format($service['cost'], 2); ?> RSD
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="service-body">
                                <div class="service-description">
                                    <i class="fas fa-clipboard-list"></i>
                                    <p><?php echo htmlspecialchars($service['description']); ?></p>
                                </div>
                                <div class="service-mileage">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <span><?php echo number_format($service['mileage'], 0); ?> km</span>
                                </div>
                            </div>

                            <!-- Card Actions -->
                            <div class="service-actions">
                                <a href="edit_service.php?id=<?php echo $service['id']; ?>" 
                                   class="service-btn btn-edit" 
                                   title="Izmeni">
                                    <i class="fas fa-edit"></i>
                                    <span>Izmeni</span>
                                </a>
                                <form method="POST" 
                                      action="../controllers/DeleteController.php" 
                                      class="d-inline delete-form" 
                                      data-service-name="<?php echo htmlspecialchars($service['description']); ?>">
                                    <input type="hidden" name="type" value="service">
                                    <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
                                    <input type="hidden" name="car_id" value="<?php echo $carId; ?>">
                                    <button type="submit" class="service-btn btn-delete" title="Obriši">
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
    </div>

    <!-- Footer -->
    <footer class="services-footer">
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
    <script src="../assets/js/services.js"></script>
</body>
</html>