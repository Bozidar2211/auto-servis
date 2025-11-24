<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: /auto-servis/views/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zahtev Poslat | Auto Servis</title>
    
    <!-- Favicon -->
    <link rel="icon" href="../assets/img/favicon.png" type="image/png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/auto-servis/assets/css/style.css">
    <link rel="stylesheet" href="/auto-servis/assets/css/request_confirmation.css">
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
                    <a class="nav-link" href="/auto-servis/views/dashboard.php">Profil</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Confirmation Content -->
<section class="confirmation-section">
    <div class="container">
        <div class="confirmation-wrapper">
            
            <!-- Success Card -->
            <div class="confirmation-card fade-in">
                <div class="success-animation">
                    <div class="checkmark-circle">
                        <svg class="checkmark" viewBox="0 0 52 52">
                            <circle class="checkmark-circle-circle" cx="26" cy="26" r="25" fill="none"/>
                            <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                        </svg>
                    </div>
                </div>

                <h1 class="confirmation-title">Zahtev je uspešno poslat!</h1>
                
                <p class="confirmation-subtitle">
                    Vaš zahtev za servisiranje je uspešno prosleđen našem timu mehaničara.
                </p>

                <div class="confirmation-details">
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-envelope-open"></i>
                        </div>
                        <div class="detail-content">
                            <h3>Zahtev primljen</h3>
                            <p>Vaš zahtev je sada u našoj listi čekanja</p>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="detail-content">
                            <h3>Čekaj odgovor</h3>
                            <p>Mehaničar će vas kontaktirati uskoro</p>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div class="detail-content">
                            <h3>Notifikacije</h3>
                            <p>Proverite obaveštenja za status zahteva</p>
                        </div>
                    </div>
                </div>

                <!-- What's Next Section -->
                <div class="whats-next">
                    <h3>Šta je sledeće?</h3>
                    <ol class="next-steps">
                        <li>
                            <span class="step-number">1</span>
                            <span class="step-text">Mehaničar će pregledati vaš zahtev</span>
                        </li>
                        <li>
                            <span class="step-number">2</span>
                            <span class="step-text">Biće vam poslano obaveštenje na email</span>
                        </li>
                        <li>
                            <span class="step-number">3</span>
                            <span class="step-text">Možete pratiti status zahteva u panelu</span>
                        </li>
                    </ol>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="/auto-servis/views/my_requests.php" class="btn btn-primary">
                        <i class="fas fa-list"></i>
                        Moji Zahtevi
                    </a>
                    <a href="/auto-servis/views/dashboard.php" class="btn btn-secondary">
                        <i class="fas fa-home"></i>
                        Nazad na Dashboard
                    </a>
                    <a href="/auto-servis/user.php?controller=request&action=showForm" class="btn btn-outline-primary">
                        <i class="fas fa-plus"></i>
                        Novi Zahtev
                    </a>
                </div>
            </div>

            <!-- Floating Cards -->
            <div class="floating-cards">
                <div class="float-card card-1">
                    <i class="fas fa-check"></i>
                </div>
                <div class="float-card card-2">
                    <i class="fas fa-star"></i>
                </div>
                <div class="float-card card-3">
                    <i class="fas fa-heart"></i>
                </div>
            </div>
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
<script src="/auto-servis/assets/js/main.js"></script>
<script src="/auto-servis/assets/js/request_confirmation.js"></script>

</body>
</html>