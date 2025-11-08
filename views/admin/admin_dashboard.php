<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Auto Servis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/auto-servis/assets/css/style.css">
    <link rel="stylesheet" href="/auto-servis/assets/css/admin.css">
</head>
<body>
    <!-- Animated Background -->
    <div class="animated-bg">
        <div class="carbon-fiber"></div>
        <div class="gradient-overlay"></div>
    </div>

    <!-- Header -->
    <header class="admin-header">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="brand-section">
                    <i class="fas fa-crown brand-icon"></i>
                    <div>
                        <h1 class="brand-title mb-0">Admin Control Panel</h1>
                        <p class="brand-subtitle mb-0">Sistem upravljanja i statistika</p>
                    </div>
                </div>
                <div class="user-section">
                    <div class="user-info">
                        <div class="user-avatar">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="user-details">
                            <span class="user-role">Administrator</span>
                            <span class="user-name"><?php echo htmlspecialchars($_SESSION['user']['username']); ?></span>
                        </div>
                    </div>
                    <a href="/auto-servis/views/logout.php" class="btn-logout">
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
                <h2>Dobrodošli u Admin Panel, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>! 👑</h2>
                <p>Centralno mesto za upravljanje sistemom, korisnicima i statistikom</p>
            </div>
            <div class="welcome-time">
                <i class="fas fa-clock"></i>
                <span id="currentTime"></span>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="quick-stats fade-in" style="animation-delay: 0.1s;">
            <div class="stat-mini stat-users">
                <i class="fas fa-users"></i>
                <div>
                    <span class="stat-mini-number">--</span>
                    <span class="stat-mini-label">Korisnici</span>
                </div>
            </div>
            <div class="stat-mini stat-cars">
                <i class="fas fa-car"></i>
                <div>
                    <span class="stat-mini-number">--</span>
                    <span class="stat-mini-label">Vozila</span>
                </div>
            </div>
            <div class="stat-mini stat-services">
                <i class="fas fa-wrench"></i>
                <div>
                    <span class="stat-mini-number">--</span>
                    <span class="stat-mini-label">Servisi</span>
                </div>
            </div>
            <div class="stat-mini stat-revenue">
                <i class="fas fa-dollar-sign"></i>
                <div>
                    <span class="stat-mini-number">--</span>
                    <span class="stat-mini-label">Prihod</span>
                </div>
            </div>
        </div>

        <!-- Admin Actions Grid -->
        <div class="admin-grid fade-in" style="animation-delay: 0.2s;">
            <!-- Card 1: User Management -->
            <div class="admin-card admin-card-primary">
                <div class="admin-card-header">
                    <div class="admin-card-icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <div class="admin-card-badge">Aktivno</div>
                </div>
                <div class="admin-card-body">
                    <h3 class="admin-card-title">Upravljanje Korisnicima</h3>
                    <p class="admin-card-desc">Pregled, izmena, brisanje i dodavanje novih korisnika sistema</p>
                    <ul class="admin-card-features">
                        <li><i class="fas fa-check"></i>Pregled svih korisnika</li>
                        <li><i class="fas fa-check"></i>Dodaj/uredi/obriši</li>
                        <li><i class="fas fa-check"></i>Upravljanje ulogama</li>
                    </ul>
                </div>
                <div class="admin-card-footer">
                    <a href="/auto-servis/admin.php?controller=user&action=index" class="btn-admin btn-admin-primary">
                        <i class="fas fa-arrow-right me-2"></i>Pregled Korisnika
                    </a>
                </div>
            </div>

            <!-- Card 2: System Statistics -->
            <div class="admin-card admin-card-info">
                <div class="admin-card-header">
                    <div class="admin-card-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="admin-card-badge">Live</div>
                </div>
                <div class="admin-card-body">
                    <h3 class="admin-card-title">Sistem Statistika</h3>
                    <p class="admin-card-desc">Ukupni podaci o vozilima, servisima, korisnicima i aktivnostima</p>
                    <ul class="admin-card-features">
                        <li><i class="fas fa-check"></i>Real-time podaci</li>
                        <li><i class="fas fa-check"></i>Grafički prikazi</li>
                        <li><i class="fas fa-check"></i>Export u PDF/Excel</li>
                    </ul>
                </div>
                <div class="admin-card-footer">
                    <a href="/auto-servis/admin.php?controller=report&action=overview" class="btn-admin btn-admin-info">
                        <i class="fas fa-chart-bar me-2"></i>Pregled Statistike
                    </a>
                </div>
            </div>

            <!-- Card 3: Cost Analysis -->
            <div class="admin-card admin-card-success">
                <div class="admin-card-header">
                    <div class="admin-card-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="admin-card-badge">Analitika</div>
                </div>
                <div class="admin-card-body">
                    <h3 class="admin-card-title">Troškovi po Korisniku</h3>
                    <p class="admin-card-desc">Detaljna analiza troškova servisa i modifikacija po korisnicima</p>
                    <ul class="admin-card-features">
                        <li><i class="fas fa-check"></i>Sortiranje po trošku</li>
                        <li><i class="fas fa-check"></i>Mesečni izveštaji</li>
                        <li><i class="fas fa-check"></i>Trend analiza</li>
                    </ul>
                </div>
                <div class="admin-card-footer">
                    <a href="/auto-servis/admin.php?controller=report&action=costsByUser" class="btn-admin btn-admin-success">
                        <i class="fas fa-file-invoice-dollar me-2"></i>Analiza Troškova
                    </a>
                </div>
            </div>

            <!-- Card 4: Service Types -->
            <div class="admin-card admin-card-warning">
                <div class="admin-card-header">
                    <div class="admin-card-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="admin-card-badge">Top</div>
                </div>
                <div class="admin-card-body">
                    <h3 class="admin-card-title">Tipovi Servisa</h3>
                    <p class="admin-card-desc">Statistika najčešće izvršenih servisa i popularnost usluga</p>
                    <ul class="admin-card-features">
                        <li><i class="fas fa-check"></i>Top 10 servisa</li>
                        <li><i class="fas fa-check"></i>Frekvencija</li>
                        <li><i class="fas fa-check"></i>Prosečni troškovi</li>
                    </ul>
                </div>
                <div class="admin-card-footer">
                    <a href="/auto-servis/admin.php?controller=report&action=topServiceTypes" class="btn-admin btn-admin-warning">
                        <i class="fas fa-list-ol me-2"></i>Top Servisi
                    </a>
                </div>
            </div>

            <!-- Card 5: System Settings (Bonus) -->
            <div class="admin-card admin-card-secondary">
                <div class="admin-card-header">
                    <div class="admin-card-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div class="admin-card-badge">Sistem</div>
                </div>
                <div class="admin-card-body">
                    <h3 class="admin-card-title">Sistemske Postavke</h3>
                    <p class="admin-card-desc">Konfiguracija sistema, backup, logovi i sigurnost</p>
                    <ul class="admin-card-features">
                        <li><i class="fas fa-check"></i>Backup baze</li>
                        <li><i class="fas fa-check"></i>System logs</li>
                        <li><i class="fas fa-check"></i>Sigurnosne opcije</li>
                    </ul>
                </div>
                <div class="admin-card-footer">
                    <a href="#" class="btn-admin btn-admin-secondary" onclick="alert('Uskoro dostupno!'); return false;">
                        <i class="fas fa-cogs me-2"></i>Postavke
                    </a>
                </div>
            </div>

            <!-- Card 6: Activity Log (Bonus) -->
            <div class="admin-card admin-card-danger">
                <div class="admin-card-header">
                    <div class="admin-card-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="admin-card-badge">Recent</div>
                </div>
                <div class="admin-card-body">
                    <h3 class="admin-card-title">Log Aktivnosti</h3>
                    <p class="admin-card-desc">Praćenje svih aktivnosti korisnika i sistema u realnom vremenu</p>
                    <ul class="admin-card-features">
                        <li><i class="fas fa-check"></i>Login istorija</li>
                        <li><i class="fas fa-check"></i>Izmene podataka</li>
                        <li><i class="fas fa-check"></i>Sigurnosni eventi</li>
                    </ul>
                </div>
                <div class="admin-card-footer">
                    <a href="#" class="btn-admin btn-admin-danger" onclick="alert('Uskoro dostupno!'); return false;">
                        <i class="fas fa-file-alt me-2"></i>Pregled Logova
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Access Bar -->
        <div class="quick-access-bar fade-in" style="animation-delay: 0.3s;">
            <h4 class="quick-access-title">
                <i class="fas fa-bolt me-2"></i>Brzi Pristup
            </h4>
            <div class="quick-access-buttons">
                <a href="/auto-servis/admin.php?controller=user&action=index" class="quick-btn">
                    <i class="fas fa-users"></i>
                    <span>Korisnici</span>
                </a>
                <a href="/auto-servis/admin.php?controller=report&action=overview" class="quick-btn">
                    <i class="fas fa-chart-pie"></i>
                    <span>Statistika</span>
                </a>
                <a href="#" class="quick-btn" onclick="showExportModal(); return false;">
                    <i class="fas fa-download"></i>
                    <span>Export</span>
                </a>
                <a href="#" class="quick-btn" onclick="showBackupModal(); return false;">
                    <i class="fas fa-database"></i>
                    <span>Backup</span>
                </a>
                <a href="#" class="quick-btn" onclick="location.reload();">
                    <i class="fas fa-sync-alt"></i>
                    <span>Refresh</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="admin-footer">
        <div class="container text-center">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Božidar AutoApp • Admin Control Panel v1.0</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/auto-servis/assets/js/main.js"></script>
    <script src="/auto-servis/assets/js/admin.js"></script>
</body>
</html>