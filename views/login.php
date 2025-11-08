<?php 
session_start(); 
require_once __DIR__ . '/../utils/SeedData.php';
SeedData::ensureAdminExists();
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijava | Auto Servis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
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
                        <a class="nav-link" href="../index.php#features">Usluge</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php#locations">Lokacije</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php#contact">Kontakt</a>
                    </li>
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                        <a href="register.php" class="btn btn-register-nav">Registracija</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Login Section -->
    <section class="auth-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <!-- Auth Card -->
                    <div class="auth-card fade-in">
                        <!-- Logo/Icon -->
                        <div class="auth-logo">
                            <div class="logo-icon">
                                <i class="fas fa-car-side"></i>
                            </div>
                            <h2>Dobrodošli nazad</h2>
                            <p>Prijavite se na vaš nalog</p>
                        </div>

                        <!-- Error Alert -->
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-custom" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Login Form -->
                        <form method="POST" action="../controllers/AuthController.php" class="auth-form">
                            <div class="form-group">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Email adresa
                                </label>
                                <input 
                                    type="email" 
                                    class="form-control" 
                                    id="email" 
                                    name="email" 
                                    placeholder="vas.email@primer.com"
                                    required
                                    autocomplete="email"
                                >
                            </div>

                            <div class="form-group">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Lozinka
                                </label>
                                <div class="password-wrapper">
                                    <input 
                                        type="password" 
                                        class="form-control" 
                                        id="password" 
                                        name="password" 
                                        placeholder="••••••••"
                                        required
                                        autocomplete="current-password"
                                    >
                                    <button type="button" class="password-toggle" onclick="togglePassword()">
                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="btn-auth btn-auth-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Prijavi se
                            </button>
                        </form>

                        <!-- Register Link -->
                        <div class="auth-footer">
                            <p>Nemate nalog?</p>
                            <a href="register.php" class="register-link">
                                Registrujte se besplatno
                                <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="auth-footer-bottom">
        <div class="container text-center">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Božidar AutoApp • Sva prava zadržana</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/auth.js"></script>
</body>
</html>