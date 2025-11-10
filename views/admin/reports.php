<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../dashboard.php');
    exit;
}

$stats = $stats ?? [];
?>

<!DOCTYPE html>
<html lang="hr">
<head>
  <meta charset="UTF-8">
  <title>📊 Izveštaji | Auto Servis</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="/auto-servis/assets/css/style.css">
  <link rel="stylesheet" href="/auto-servis/assets/css/reports.css">
</head>
<body data-page="reports">

<!-- Favicon -->
    <link rel="icon" href="/auto-servis/assets/img/favicon.png" type="image/png">
<!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Pozadina -->
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
            <h1 class="header-title">Sistem statistika</h1>
            <p class="header-subtitle">Podaci</p>
        </div>
    </div>
</section>

<!-- Sadržaj -->
<div class="container mt-5">
  <div class="section-header fade-in">
    <h3><i class="bi bi-graph-up-arrow me-2"></i>Pregled sistema</h3>
  </div>

  <div class="stats-grid fade-in">
    <div class="stat-card">
      <i class="bi bi-people-fill stat-icon"></i>
      <div>
        <div class="stat-number" data-count="<?php echo $stats['user_count'] ?? 0; ?>">0</div>
        <div class="stat-label">Korisnika</div>
      </div>
    </div>
    <div class="stat-card">
      <i class="bi bi-car-front-fill stat-icon"></i>
      <div>
        <div class="stat-number" data-count="<?php echo $stats['car_count'] ?? 0; ?>">0</div>
        <div class="stat-label">Automobila</div>
      </div>
    </div>
    <div class="stat-card">
      <i class="bi bi-tools stat-icon"></i>
      <div>
        <div class="stat-number" data-count="<?php echo $stats['service_count'] ?? 0; ?>">0</div>
        <div class="stat-label">Servisa</div>
      </div>
    </div>
    <div class="stat-card">
      <i class="bi bi-stars stat-icon"></i>
      <div>
        <div class="stat-number" data-count="<?php echo $stats['mod_count'] ?? 0; ?>">0</div>
        <div class="stat-label">Modifikacija</div>
      </div>
    </div>
    <div class="stat-card">
      <i class="bi bi-bell-fill stat-icon"></i>
      <div>
        <div class="stat-number" data-count="<?php echo $stats['reminder_count'] ?? 0; ?>">0</div>
        <div class="stat-label">Podsetnika</div>
      </div>
    </div>
  </div>

  <div class="quick-actions mt-5 fade-in">
    <a href="/auto-servis/admin.php?controller=report&action=costsByUser" class="action-card">
      <div class="action-icon"><i class="bi bi-cash-coin"></i></div>
      <div class="action-content">
        <h6>Troškovi po korisniku</h6>
        <p>Pregledaj ukupne troškove po korisnicima</p>
      </div>
    </a>
    <a href="/auto-servis/admin.php?controller=report&action=topServiceTypes" class="action-card">
      <div class="action-icon"><i class="bi bi-wrench-adjustable-circle"></i></div>
      <div class="action-content">
        <h6>Najčešći servisi</h6>
        <p>Statistika najčešćih tipova servisa</p>
      </div>
    </a>
  </div>
</div>

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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="/auto-servis/assets/js/main.js"></script>
<script src="/auto-servis/assets/js/reports.js"></script>
</body>
</html>
