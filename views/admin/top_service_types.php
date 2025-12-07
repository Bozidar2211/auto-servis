<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../dashboard.php');
    exit;
}

$types = $types ?? [];

// Calculate total services
$totalServices = array_sum(array_column($types, 'count'));
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Top Servisi | Auto Servis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Favicon -->
    <link rel="icon" href="/auto-servis/assets/img/favicon.png" type="image/png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/auto-servis/assets/css/style.css">
    <link rel="stylesheet" href="/auto-servis/assets/css/top_services.css">
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
        <a class="navbar-brand" href="/auto-servis/index.php">
            <i class="fas fa-car-side"></i>
            Auto Servis
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="/auto-servis/admin.php?controller=user&action=dashboard">
                        <i class="fas fa-tachometer-alt me-1"></i>Admin Panel
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/auto-servis/admin.php?controller=report&action=overview">
                        <i class="fas fa-chart-bar me-1"></i>Statistika
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/auto-servis/views/logout.php">
                        <i class="fas fa-sign-out-alt me-1"></i>Odjava
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Header Section -->
<section class="page-header">
    <div class="container">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-trophy"></i>
            </div>
            <h1 class="header-title">Top Tipovi Servisa</h1>
            <p class="header-subtitle">Statistika najčešće izvršenih servisa</p>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="content-section">
    <div class="container">
        <?php if (empty($types)): ?>
            <!-- Empty State -->
            <div class="empty-state">
                <i class="fas fa-tools"></i>
                <h3>Nema dostupnih podataka</h3>
                <p>Trenutno ne postoje podaci o izvršenim servisima.</p>
                <a href="/auto-servis/admin.php?controller=user&action=dashboard" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Nazad na Admin Panel
                </a>
            </div>
        <?php else: ?>
            <!-- Summary Stats -->
            <div class="summary-stats mb-4">
                <div class="summary-card">
                    <div class="summary-icon">
                        <i class="fas fa-list-ol"></i>
                    </div>
                    <div class="summary-content">
                        <div class="summary-value"><?php echo count($types); ?></div>
                        <div class="summary-label">Ukupno tipova</div>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-icon">
                        <i class="fas fa-wrench"></i>
                    </div>
                    <div class="summary-content">
                        <div class="summary-value"><?php echo $totalServices; ?></div>
                        <div class="summary-label">Ukupno servisa</div>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="summary-content">
                        <div class="summary-value"><?php echo $types[0]['description'] ?? 'N/A'; ?></div>
                        <div class="summary-label">Najpopularniji</div>
                    </div>
                </div>
            </div>

            <!-- Service Rankings -->
            <div class="rankings-container">
                <div class="rankings-header">
                    <h3>
                        <i class="fas fa-ranking-star me-2"></i>
                        Top <?php echo min(10, count($types)); ?> Servisa
                    </h3>
                    <div class="rankings-actions">
                        <button class="btn-action" onclick="exportToCSV()">
                            <i class="fas fa-download me-2"></i>Preuzmi CSV
                        </button>
                        <button class="btn-action" onclick="printTable()">
                            <i class="fas fa-print me-2"></i>Štampaj
                        </button>
                    </div>
                </div>

                <div class="rankings-list">
                    <?php foreach ($types as $index => $service): ?>
                        <?php
                        $rank = $index + 1;
                        $percentage = ($service['count'] / $totalServices) * 100;
                        
                        // Determine rank badge style
                        $rankClass = '';
                        $rankIcon = '';
                        if ($rank === 1) {
                            $rankClass = 'rank-gold';
                            $rankIcon = 'fa-crown';
                        } elseif ($rank === 2) {
                            $rankClass = 'rank-silver';
                            $rankIcon = 'fa-medal';
                        } elseif ($rank === 3) {
                            $rankClass = 'rank-bronze';
                            $rankIcon = 'fa-award';
                        } else {
                            $rankClass = 'rank-default';
                            $rankIcon = 'fa-certificate';
                        }
                        ?>
                        <div class="ranking-item" style="animation-delay: <?php echo $index * 0.05; ?>s;">
                            <div class="rank-badge <?php echo $rankClass; ?>">
                                <i class="fas <?php echo $rankIcon; ?>"></i>
                                <span class="rank-number">#<?php echo $rank; ?></span>
                            </div>
                            
                            <div class="service-info">
                                <div class="service-name">
                                    <i class="fas fa-wrench me-2"></i>
                                    <?php echo htmlspecialchars($service['description']); ?>
                                </div>
                                
                                <div class="service-stats">
                                    <div class="stat-item">
                                        <i class="fas fa-hashtag"></i>
                                        <span><?php echo $service['count']; ?> puta</span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="fas fa-percentage"></i>
                                        <span><?php echo number_format($percentage, 1); ?>%</span>
                                    </div>
                                </div>
                                
                                <div class="progress-bar-container">
                                    <div class="progress-bar" 
                                         style="width: <?php echo $percentage; ?>%"
                                         data-percentage="<?php echo $percentage; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Data Table (Alternative View) -->
            <div class="table-container mt-5">
                <div class="table-header">
                    <h3><i class="fas fa-table me-2"></i>Detaljna tabela</h3>
                    <button class="btn-toggle" onclick="toggleTableView()">
                        <i class="fas fa-eye"></i>
                        <span>Prikaži/Sakrij</span>
                    </button>
                </div>
                
                <div class="table-responsive" id="dataTable" style="display: none;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th onclick="sortTable(0)">
                                    Rang <i class="fas fa-sort"></i>
                                </th>
                                <th onclick="sortTable(1)">
                                    Opis servisa <i class="fas fa-sort"></i>
                                </th>
                                <th onclick="sortTable(2)" class="text-end">
                                    Broj izvršenih <i class="fas fa-sort"></i>
                                </th>
                                <th class="text-end">
                                    Procenat
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($types as $index => $service): ?>
                                <?php $percentage = ($service['count'] / $totalServices) * 100; ?>
                                <tr class="table-row">
                                    <td class="text-center">
                                        <span class="rank-pill">#<?php echo $index + 1; ?></span>
                                    </td>
                                    <td>
                                        <div class="service-cell">
                                            <i class="fas fa-wrench"></i>
                                            <span><?php echo htmlspecialchars($service['description']); ?></span>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="count-value"><?php echo $service['count']; ?></span>
                                    </td>
                                    <td class="text-end">
                                        <span class="percentage-value"><?php echo number_format($percentage, 1); ?>%</span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <!-- Back Button -->
        <div class="text-center mt-5">
            <a href="/auto-servis/admin.php?controller=user&action=dashboard" class="btn-back">
                <i class="fas fa-arrow-left me-2"></i>Nazad na Admin Panel
            </a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h5><i class="fas fa-car-side me-2"></i>Auto Servis</h5>
                <p class="text-muted">Premium platforma za digitalno upravljanje servisima.</p>
            </div>
            <div class="footer-section">
                <h5>Admin Panel</h5>
                <ul>
                    <li><a href="/auto-servis/admin.php?controller=user&action=dashboard">Dashboard</a></li>
                    <li><a href="/auto-servis/admin.php?controller=report&action=overview">Statistika</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Božidar AutoApp | Sva prava zadržana</p>
        </div>
    </div>
</footer>

<!-- Scroll to top button -->
<div class="scroll-top" id="scrollTop">
    <i class="fas fa-arrow-up"></i>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JavaScript -->
<script src="/auto-servis/assets/js/top_services.js"></script>

</body>
</html>