<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$carId = $_GET['car_id'] ?? null;
$prefill = $_SESSION['prefill_request'] ?? null;

if (!$carId) {
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj Servis | Auto Servis</title>
    
    <!-- Favicon -->
    <link rel="icon" href="../assets/img/favicon.png" type="image/png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/add_service.css">
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
                    <a class="nav-link" href="dashboard.php">Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="services.php?car_id=<?php echo htmlspecialchars($carId); ?>">Servisi</a>
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
                <i class="fas fa-tools"></i>
            </div>
            <h1 class="header-title">Dodaj Novi Servis</h1>
            <p class="header-subtitle">Zabeležite servis obavljanja na vašem automobilu</p>
        </div>
        
        <!-- Progress Indicator -->
        <div class="progress-indicator">
            <div class="progress-step active">
                <div class="step-circle">1</div>
                <span class="step-label">Detalji</span>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <div class="step-circle">2</div>
                <span class="step-label">Završeno</span>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="content-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <!-- Prefill Alert -->
                <?php if ($prefill): ?>
                    <div class="info-card fade-in" style="background: rgba(40, 167, 69, 0.1); border-color: rgba(40, 167, 69, 0.3);">
                        <div class="info-icon" style="background: rgba(40, 167, 69, 0.2);">
                            <i class="fas fa-check-circle" style="color: #28a745;"></i>
                        </div>
                        <div class="info-content">
                            <h5 style="color: #28a745;">Podaci iz zahteva</h5>
                            <p>Servis se dodaje na osnovu zahteva za vozilo: <strong><?php echo htmlspecialchars($prefill['car_model']); ?></strong>. Opis je već popunjen.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Info Card -->
                    <div class="info-card fade-in">
                        <div class="info-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <div class="info-content">
                            <h5>Savet:</h5>
                            <p>Detaljno zabeležite sve servise kako biste pratili istoriju održavanja i troškove vašeg automobila.</p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Service Form -->
                <div class="service-form-container fade-in" style="animation-delay: 0.1s;">
                    <div class="form-header">
                        <i class="fas fa-wrench"></i>
                        <h3>Osnovni Podaci Servisa</h3>
                    </div>
                    
                    <form id="addServiceForm" method="POST" action="../controllers/AddServiceController.php">
                        
                        <!-- Hidden Car ID -->
                        <input type="hidden" name="car_id" value="<?php echo htmlspecialchars($carId); ?>">

                        <!-- Service Date Group -->
                        <div class="form-group">
                            <label for="service_date" class="form-label">
                                <i class="fas fa-calendar"></i> Datum Servisa
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-calendar-alt input-icon"></i>
                                <input 
                                    type="date" 
                                    class="form-control" 
                                    id="service_date" 
                                    name="service_date" 
                                    required
                                >
                                <i class="fas fa-question-circle input-validator"></i>
                            </div>
                            <div class="form-error" id="service_date-error"></div>
                            <div class="form-help">Datum kada je obavljen servis</div>
                        </div>

                        <!-- Service Type Group -->
                        <div class="form-group">
                            <label for="service_type" class="form-label">
                                <i class="fas fa-tags"></i> Tip Servisa
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-list input-icon"></i>
                                <select class="form-control" id="service_type" name="service_type" required>
                                    <option value="">-- Odaberi tip servisa --</option>
                                    <option value="Redovan servis">Redovan servis</option>
                                    <option value="Zamena ulja">Zamena ulja</option>
                                    <option value="Servis kočnica">Servis kočnica</option>
                                    <option value="Zamena filtera">Zamena filtera</option>
                                    <option value="Pregled suspenzije">Pregled suspenzije</option>
                                    <option value="Popravka">Popravka</option>
                                    <option value="Održavanje">Održavanje</option>
                                    <option value="Dijagnostika">Dijagnostika</option>
                                    <option value="Ostalo">Ostalo</option>
                                </select>
                                <i class="fas fa-question-circle input-validator"></i>
                            </div>
                            <div class="form-error" id="service_type-error"></div>
                        </div>

                        <!-- Description Group -->
                        <div class="form-group">
                            <label for="description" class="form-label">
                                <i class="fas fa-file-alt"></i> Opis Servisa
                            </label>
                            <textarea 
                                class="form-control textarea-control" 
                                id="description" 
                                name="description" 
                                rows="4"
                                required
                                placeholder="Detaljno objasni šta je urađeno tokom servisa..."
                            ><?php echo isset($prefill['description']) ? htmlspecialchars($prefill['description']) : ''; ?></textarea>
                            <div class="form-error" id="description-error"></div>
                            <div class="form-help">Što detaljniji opis, to bolje za budućnost</div>
                        </div>

                        <!-- Cost Group -->
                        <div class="form-group">
                            <label for="cost" class="form-label">
                                <i class="fas fa-money-bill"></i> Cena Servisa (RSD)
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-coins input-icon"></i>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    id="cost" 
                                    name="cost" 
                                    step="0.01"
                                    min="0"
                                    required
                                    placeholder="npr. 5000"
                                >
                                <i class="fas fa-question-circle input-validator"></i>
                            </div>
                            <div class="form-error" id="cost-error"></div>
                            <div class="form-help">Unesite ukupnu cenu servisa</div>
                        </div>

                        <!-- Mileage Group -->
                        <div class="form-group">
                            <label for="mileage" class="form-label">
                                <i class="fas fa-road"></i> Kilometraža (km)
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-tachometer-alt input-icon"></i>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    id="mileage" 
                                    name="mileage" 
                                    min="0"
                                    required
                                    placeholder="npr. 45000"
                                >
                                <i class="fas fa-question-circle input-validator"></i>
                            </div>
                            <div class="form-error" id="mileage-error"></div>
                            <div class="form-help">Trenutna kilometraža vozila</div>
                        </div>

                        <!-- Service Provider Group (Optional) -->
                        <div class="form-group">
                            <label for="service_provider" class="form-label">
                                <i class="fas fa-store"></i> Servisna Radnja (opciono)
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-building input-icon"></i>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="service_provider" 
                                    name="service_provider" 
                                    placeholder="Naziv servisne radnje..."
                                >
                                <i class="fas fa-question-circle input-validator"></i>
                            </div>
                            <div class="form-error" id="service_provider-error"></div>
                        </div>

                        <!-- Notes Group (Optional) -->
                        <div class="form-group">
                            <label for="notes" class="form-label">
                                <i class="fas fa-sticky-note"></i> Napomene (opciono)
                            </label>
                            <textarea 
                                class="form-control textarea-control" 
                                id="notes" 
                                name="notes" 
                                rows="3"
                                placeholder="Bilo koje dodatne napomene..."
                            ></textarea>
                            <div class="form-error" id="notes-error"></div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <a href="services.php?car_id=<?php echo htmlspecialchars($carId); ?>" class="btn-cancel">
                                <i class="fas fa-arrow-left"></i>
                                <span>Nazad</span>
                            </a>
                            <button type="submit" class="btn-submit">
                                <span>Sačuvaj Servis</span>
                                <i class="fas fa-save"></i>
                                <i class="fas fa-spinner fa-spin btn-loader"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Popular Services -->
                <div class="popular-services fade-in" style="animation-delay: 0.2s;">
                    <h5>
                        <i class="fas fa-star"></i>
                        Česti Tipovi Servisa
                    </h5>
                    <div class="service-grid">
                        <button type="button" class="service-btn" data-type="Redovan servis">
                            <i class="fas fa-tools"></i>
                            Redovan Servis
                        </button>
                        <button type="button" class="service-btn" data-type="Zamena ulja">
                            <i class="fas fa-oil-can"></i>
                            Zamena Ulja
                        </button>
                        <button type="button" class="service-btn" data-type="Servis kočnica">
                            <i class="fas fa-hand-paper"></i>
                            Kočnice
                        </button>
                        <button type="button" class="service-btn" data-type="Zamena filtera">
                            <i class="fas fa-filter"></i>
                            Filteri
                        </button>
                        <button type="button" class="service-btn" data-type="Pregled suspenzije">
                            <i class="fas fa-arrow-down"></i>
                            Suspenzija
                        </button>
                        <button type="button" class="service-btn" data-type="Dijagnostika">
                            <i class="fas fa-microscope"></i>
                            Dijagnostika
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
<script src="../assets/js/add_service.js"></script>

</body>
</html>