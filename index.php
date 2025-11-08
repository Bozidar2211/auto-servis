<?php session_start(); ?>

<!DOCTYPE html>
<html lang="sr">
<head>
  <meta charset="UTF-8">
  <title>Auto Servis | Premium Auto Održavanje</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Profesionalno održavanje vozila, online zakazivanje, servisna istorija i tuning.">
  <meta name="keywords" content="auto servis, održavanje vozila, tuning, online zakazivanje, servisna istorija">
  <meta name="author" content="Božidar AutoApp">
  
  <!-- Favicon -->
  <link rel="icon" href="assets/img/favicon.png" type="image/png">
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/index.css">
  <link rel="stylesheet" href="assets/css/map.css">
</head>
<body>

<!-- Animated Background -->
<div class="animated-bg"></div>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <i class="fas fa-car-side"></i>
      Auto Servis
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item">
          <a class="nav-link" href="#features">Usluge</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#locations">Lokacije</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#contact">Kontakt</a>
        </li>
        <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
          <a href="views/login.php" class="btn btn-login">Prijava</a>
          <a href="views/register.php" class="btn btn-register">Registracija</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
  <div class="container">
    <div class="hero-content">
      <h1 class="hero-title">Premium Auto Održavanje</h1>
      <p class="hero-subtitle">Digitalna revolucija u servisiranju vozila</p>
      <div class="hero-cta">
        <a href="views/register.php" class="btn-hero btn-hero-primary">
          <i class="fas fa-rocket me-2"></i>Počni Besplatno
        </a>
        <a href="#features" class="btn-hero btn-hero-secondary">
          <i class="fas fa-play-circle me-2"></i>Saznaj Više
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
  <div class="container">
    <div class="stats-grid">
      <div class="stat-item">
        <span class="stat-number" data-count="5000">0</span>
        <span class="stat-label">Korisnika</span>
      </div>
      <div class="stat-item">
        <span class="stat-number" data-count="15000">0</span>
        <span class="stat-label">Servisa</span>
      </div>
      <div class="stat-item">
        <span class="stat-number" data-count="50">0</span>
        <span class="stat-label">Lokacija</span>
      </div>
      <div class="stat-item">
        <span class="stat-number" data-count="98">0</span>
        <span class="stat-label">% Zadovoljnih</span>
      </div>
    </div>
  </div>
</section>

<!-- Features Section -->
<section class="features-section" id="features">
  <div class="container">
    <h2 class="section-title">Naše Usluge</h2>
    
    <div class="features-grid">
      <!-- Feature 1: Servisna Istorija -->
      <div class="feature-card">
        <div class="feature-bg" style="background-image: url('https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?w=800&q=80');"></div>
        <div class="feature-content">
          <i class="fas fa-history feature-icon"></i>
          <h3 class="feature-title">Servisna Istorija</h3>
          <p class="feature-desc">Kompletna digitalna evidencija svih servisa, modifikacija i troškova vašeg vozila na jednom mestu.</p>
        </div>
      </div>

      <!-- Feature 2: Online Zakazivanje -->
      <div class="feature-card">
        <div class="feature-bg" style="background-image: url('https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?w=800&q=80');"></div>
        <div class="feature-content">
          <i class="fas fa-calendar-check feature-icon"></i>
          <h3 class="feature-title">Online Zakazivanje</h3>
          <p class="feature-desc">Zakažite termin direktno sa mehaničarom, dobijte procenu cene i pratite status vašeg servisa u realnom vremenu.</p>
        </div>
      </div>

      <!-- Feature 3: Tuning -->
      <div class="feature-card">
        <div class="feature-bg" style="background-image: url('https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=800&q=80');"></div>
        <div class="feature-content">
          <i class="fas fa-cogs feature-icon"></i>
          <h3 class="feature-title">Tuning i Modifikacije</h3>
          <p class="feature-desc">Evidentirajte sve modifikacije, performanse i unapređenja vašeg vozila sa detaljnom dokumentacijom.</p>
        </div>
      </div>

      <!-- Feature 4: Podsetnici -->
      <div class="feature-card">
        <div class="feature-bg" style="background-image: url('https://images.unsplash.com/photo-1625047509248-ec889cbff17f?w=800&q=80');"></div>
        <div class="feature-content">
          <i class="fas fa-bell feature-icon"></i>
          <h3 class="feature-title">Pametni Podsetnici</h3>
          <p class="feature-desc">Automatski podsetnici za zamenu ulja, registraciju, tehnički pregled i sve ostale servisne intervale.</p>
        </div>
      </div>

      <!-- Feature 5: Statistika -->
      <div class="feature-card">
        <div class="feature-bg" style="background-image: url('https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&q=80');"></div>
        <div class="feature-content">
          <i class="fas fa-chart-line feature-icon"></i>
          <h3 class="feature-title">Analitika i Statistika</h3>
          <p class="feature-desc">Detaljni grafikoni troškova, pređene kilometraže i kompletna statistika održavanja vašeg vozila.</p>
        </div>
      </div>

      <!-- Feature 6: Stručnjaci -->
      <div class="feature-card">
        <div class="feature-bg" style="background-image: url('https://images.unsplash.com/photo-1581092921461-eab62e97a780?w=800&q=80');"></div>
        <div class="feature-content">
          <i class="fas fa-users feature-icon"></i>
          <h3 class="feature-title">Provereni Stručnjaci</h3>
          <p class="feature-desc">Pristup mreži sertifikovanih mehaničara i ovlašćenih servisa sa garantovanim kvalitetom usluga.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Map Section -->
<section class="map-section" id="locations">
  <div class="container">
    <h2 class="section-title">Naše Lokacije</h2>
    
    <div class="map-container">
      <div id="map"></div>
    </div>

    <div class="locations-list">
      <div class="location-card">
        <div class="location-name">
          <i class="fas fa-map-marker-alt"></i>
          Beograd - Centar
        </div>
        <div class="location-address">Knez Mihailova 15, Beograd</div>
      </div>
      <div class="location-card">
        <div class="location-name">
          <i class="fas fa-map-marker-alt"></i>
          Novi Sad
        </div>
        <div class="location-address">Bulevar Oslobođenja 100, Novi Sad</div>
      </div>
      <div class="location-card">
        <div class="location-name">
          <i class="fas fa-map-marker-alt"></i>
          Niš
        </div>
        <div class="location-address">Obrenovićeva 25, Niš</div>
      </div>
      <div class="location-card">
        <div class="location-name">
          <i class="fas fa-map-marker-alt"></i>
          Kragujevac
        </div>
        <div class="location-address">King Cross Shopping Centar, Kragujevac</div>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer id="contact">
  <div class="container">
    <div class="footer-content">
      <!-- Company Info -->
      <div class="footer-section">
        <h5><i class="fas fa-car-side me-2"></i>Auto Servis</h5>
        <p class="text-muted">Premium platforma za digitalno upravljanje servisima i održavanjem vozila.</p>
        <div class="social-icons">
          <a href="#" class="social-icon" aria-label="Facebook">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a href="#" class="social-icon" aria-label="Instagram">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="#" class="social-icon" aria-label="Twitter">
            <i class="fab fa-twitter"></i>
          </a>
          <a href="#" class="social-icon" aria-label="LinkedIn">
            <i class="fab fa-linkedin-in"></i>
          </a>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="footer-section">
        <h5>Brzi Linkovi</h5>
        <ul>
          <li><a href="#features">Usluge</a></li>
          <li><a href="#locations">Lokacije</a></li>
          <li><a href="views/register.php">Registracija</a></li>
          <li><a href="views/login.php">Prijava</a></li>
        </ul>
      </div>

      <!-- Support -->
      <div class="footer-section">
        <h5>Podrška</h5>
        <ul>
          <li><a href="#">Česta pitanja</a></li>
          <li><a href="#">Uslovi korišćenja</a></li>
          <li><a href="#">Politika privatnosti</a></li>
          <li><a href="#">Kontakt</a></li>
        </ul>
      </div>

      <!-- Contact -->
      <div class="footer-section">
        <h5>Kontakt</h5>
        <ul>
          <li><i class="fas fa-phone me-2 text-warning"></i>+381 11 123 4567</li>
          <li><i class="fas fa-envelope me-2 text-warning"></i>info@autoservis.rs</li>
          <li><i class="fas fa-clock me-2 text-warning"></i>Pon-Pet: 08:00-20:00</li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <p class="mb-0">&copy; <?= date('Y') ?> Božidar AutoApp | Sva prava zadržana</p>
    </div>
  </div>
</footer>

<!-- Scroll to top button -->
<div class="scroll-top" id="scrollTop">
  <i class="fas fa-arrow-up"></i>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

<!-- Custom JavaScript -->
<script src="assets/js/index.js"></script>
<script src="assets/js/map.js"></script>

</body>
</html>