<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../dashboard.php');
    exit;
}

$costs = $costs ?? [];
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Troškovi po korisniku | Auto Servis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Favicon -->
    <link rel="icon" href="/auto-servis/assets/img/favicon.png" type="image/png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/auto-servis/assets/css/cost_by_user.css">
</head>
<body>

<!-- Animated Background -->
<div class="animated-bg"></div>

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
                <i class="fas fa-chart-line"></i>
            </div>
            <h1 class="header-title">Troškovi po korisniku</h1>
            <p class="header-subtitle">Pregled ukupnih troškova svih korisnika sistema</p>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="content-section">
    <div class="container">
        <?php if (empty($costs)): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>Nema dostupnih podataka</h3>
                <p>Trenutno ne postoje podaci o troškovima korisnika.</p>
            </div>
        <?php else: ?>
            <!-- Stats Cards -->
            <div class="stats-grid mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?php echo count($costs); ?></div>
                        <div class="stat-label">Ukupno korisnika</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">
                            <?php echo number_format(array_sum(array_column($costs, 'total_cost')), 0); ?> RSD
                        </div>
                        <div class="stat-label">Ukupni troškovi</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">
                            <?php echo number_format(array_sum(array_column($costs, 'total_cost')) / count($costs), 0); ?> RSD
                        </div>
                        <div class="stat-label">Prosečan trošak</div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="data-table-container">
                <div class="table-header">
                    <h3><i class="fas fa-table me-2"></i>Detaljna tabela troškova</h3>
                    <div class="table-actions">
                        <button class="btn-action" onclick="exportToCSV()">
                            <i class="fas fa-download me-2"></i>Preuzmi CSV
                        </button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="data-table" id="costsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th onclick="sortTable(1)">
                                    Korisnik <i class="fas fa-sort"></i>
                                </th>
                                <th onclick="sortTable(2)" class="text-end">
                                    Ukupan trošak (RSD) <i class="fas fa-sort"></i>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $index = 1; foreach ($costs as $row): ?>
                                <tr class="table-row">
                                    <td class="text-muted"><?php echo $index++; ?></td>
                                    <td>
                                        <div class="user-cell">
                                            <div class="user-avatar">
                                                <?php echo strtoupper(substr($row['username'], 0, 2)); ?>
                                            </div>
                                            <span class="user-name"><?php echo htmlspecialchars($row['username']); ?></span>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="cost-value"><?php echo number_format($row['total_cost'], 2); ?> RSD</span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <!-- Back Button -->
        <div class="text-center mt-4">
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
                    <li><a href="#">Upravljanje korisnicima</a></li>
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
<script src="/auto-servis/assets/js/cost_by_user.js"></script>

</body>
</html>