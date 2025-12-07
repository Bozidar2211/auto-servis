<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj Automobil | Auto Servis</title>
    
    <!-- Favicon -->
    <link rel="icon" href="../assets/img/favicon.png" type="image/png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/add_car.css">
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
                <i class="fas fa-car"></i>
            </div>
            <h1 class="header-title">Dodaj Novo Vozilo</h1>
            <p class="header-subtitle">Registruj automobil u svoj garažni sistem</p>
        </div>
        
        <!-- Progress Indicator -->
        <div class="progress-indicator">
            <div class="progress-step active">
                <div class="step-circle">
                    <i class="fas fa-info-circle"></i>
                </div>
                <span class="step-label">Osnovni podaci</span>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <div class="step-circle">
                    <i class="fas fa-check"></i>
                </div>
                <span class="step-label">Potvrda</span>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="content-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <!-- Info Card -->
                <div class="info-card fade-in">
                    <div class="info-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <div class="info-content">
                        <h5>Saveti za registraciju</h5>
                        <p>Unesite tačne podatke o vašem vozilu kako biste lakše pratili servisnu istoriju, troškove i podsetnika.</p>
                    </div>
                </div>

                <!-- Car Form -->
                <div class="car-form-container fade-in" style="animation-delay: 0.2s;">
                    <div class="form-header">
                        <i class="fas fa-clipboard-list"></i>
                        <h3>Podaci o vozilu</h3>
                    </div>

                    <form method="POST" action="../controllers/AddCarController.php" id="addCarForm">
                        
                        <!-- Brand Field -->
                        <div class="form-group">
                            <label for="brand" class="form-label">
                                <i class="fas fa-copyright me-2"></i>Marka vozila
                            </label>
                            <div class="input-wrapper">
                                <span class="input-icon">
                                    <i class="fas fa-car-side"></i>
                                </span>
                                <input 
                                    type="text" 
                                    name="brand" 
                                    id="brand" 
                                    class="form-control"
                                    placeholder="npr. BMW, Mercedes, Audi..."
                                    required
                                    minlength="2"
                                    maxlength="50"
                                    autocomplete="off">
                                <div class="input-validator"></div>
                            </div>
                            <div class="form-help">Unesite proizvođača vozila</div>
                            <div class="form-error" id="brand-error"></div>
                        </div>

                        <!-- Model Field -->
                        <div class="form-group">
                            <label for="model" class="form-label">
                                <i class="fas fa-tag me-2"></i>Model vozila
                            </label>
                            <div class="input-wrapper">
                                <span class="input-icon">
                                    <i class="fas fa-car"></i>
                                </span>
                                <input 
                                    type="text" 
                                    name="model" 
                                    id="model" 
                                    class="form-control"
                                    placeholder="npr. X5, E-Class, A4..."
                                    required
                                    minlength="1"
                                    maxlength="50"
                                    autocomplete="off">
                                <div class="input-validator"></div>
                            </div>
                            <div class="form-help">Unesite model vozila</div>
                            <div class="form-error" id="model-error"></div>
                        </div>

                        <!-- Year Field -->
                        <div class="form-group">
                            <label for="year" class="form-label">
                                <i class="fas fa-calendar-alt me-2"></i>Godina proizvodnje
                            </label>
                            <div class="input-wrapper">
                                <span class="input-icon">
                                    <i class="fas fa-calendar"></i>
                                </span>
                                <input 
                                    type="number" 
                                    name="year" 
                                    id="year" 
                                    class="form-control"
                                    placeholder="<?php echo date('Y'); ?>"
                                    required
                                    min="1900"
                                    max="<?php echo date('Y') + 1; ?>"
                                    autocomplete="off">
                                <div class="input-validator"></div>
                            </div>
                            <div class="form-help">Godina mora biti između 1900 i <?php echo date('Y') + 1; ?></div>
                            <div class="form-error" id="year-error"></div>
                        </div>

                        <!-- Registration Field -->
                        <div class="form-group">
                            <label for="registration" class="form-label">
                                <i class="fas fa-id-card me-2"></i>Registarska oznaka
                            </label>
                            <div class="input-wrapper">
                                <span class="input-icon">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                <input 
                                    type="text" 
                                    name="registration" 
                                    id="registration" 
                                    class="form-control registration-input"
                                    placeholder="BG-123-AB"
                                    required
                                    minlength="5"
                                    maxlength="10"
                                    autocomplete="off"
                                    style="text-transform: uppercase;">
                                <div class="input-validator"></div>
                            </div>
                            <div class="form-help">Format: BG-123-AB (5-10 karaktera, slova i brojevi)</div>
                            <div class="form-error" id="registration-error"></div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-save me-2"></i>
                                <span>Dodaj vozilo</span>
                                <div class="btn-loader">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                            </button>
                            <a href="dashboard.php" class="btn-cancel">
                                <i class="fas fa-times me-2"></i>
                                Otkaži
                            </a>
                        </div>

                    </form>
                </div>

                <!-- Popular Brands -->
                <div class="popular-brands fade-in" style="animation-delay: 0.4s;">
                    <h5><i class="fas fa-star me-2"></i>Popularne marke</h5>
                    <div class="brands-grid">
                        <button type="button" class="brand-btn" data-brand="BMW">
                            <i class="fas fa-car"></i>
                            BMW
                        </button>
                        <button type="button" class="brand-btn" data-brand="Mercedes">
                            <i class="fas fa-car"></i>
                            Mercedes
                        </button>
                        <button type="button" class="brand-btn" data-brand="Audi">
                            <i class="fas fa-car"></i>
                            Audi
                        </button>
                        <button type="button" class="brand-btn" data-brand="Volkswagen">
                            <i class="fas fa-car"></i>
                            VW
                        </button>
                        <button type="button" class="brand-btn" data-brand="Toyota">
                            <i class="fas fa-car"></i>
                            Toyota
                        </button>
                        <button type="button" class="brand-btn" data-brand="Honda">
                            <i class="fas fa-car"></i>
                            Honda
                        </button>
                        <button type="button" class="brand-btn" data-brand="Ford">
                            <i class="fas fa-car"></i>
                            Ford
                        </button>
                        <button type="button" class="brand-btn" data-brand="Opel">
                            <i class="fas fa-car"></i>
                            Opel
                        </button>
                    </div>
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
<script src="../assets/js/main.js"></script>
<script src="../assets/js/add_car.js"></script>

</body>
</html>