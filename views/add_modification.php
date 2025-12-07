<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../controllers/ModificationController.php';

$carId = $_GET['car_id'] ?? null;

if (!$carId) {
    header('Location: dashboard.php');
    exit;
}

// Get car details
require_once __DIR__ . '/../models/Car.php';
$car = Car::getById($carId);

if (!$car || $car['user_id'] != $_SESSION['user']['id']) {
    header('Location: dashboard.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj Modifikaciju | Auto Servis</title>
    
    <!-- Favicon -->
    <link rel="icon" href="../assets/img/favicon.png" type="image/png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/add_modification.css">
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
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="modifications.php?car_id=<?= $car['id'] ?>">Moje Modifikacije</a>
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
                <i class="fas fa-wrench"></i>
            </div>
            <h1 class="header-title">Dodaj Novu Modifikaciju</h1>
            <p class="header-subtitle">
                Za automobil: <strong><?= htmlspecialchars($car['brand'] . ' ' . $car['model'] . ' (' . $car['year'] . ')') ?></strong>
            </p>
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
                
                <!-- Car Info Card -->
                <div class="info-card fade-in" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="info-icon">
                        <i class="fas fa-car"></i>
                    </div>
                    <div class="info-content">
                        <h5>Automobil:</h5>
                        <p style="font-size: 1.1rem; font-weight: 600;">
                            <?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?>
                            <span style="opacity: 0.8; font-weight: 400;"> • Godište: <?= htmlspecialchars($car['year']) ?></span>
                        </p>
                    </div>
                </div>

                <!-- Modification Form -->
                <div class="modification-form-container fade-in" style="animation-delay: 0.1s;">
                    <div class="form-header">
                        <i class="fas fa-tools"></i>
                        <h3>Osnovni Podaci Modifikacije</h3>
                    </div>
                    
                    <form id="addModificationForm" method="POST" action="../controllers/AddModificationController.php">
                        
                        <!-- Hidden Car ID Field -->
                        <input type="hidden" name="car_id" value="<?= htmlspecialchars($carId) ?>">

                        <!-- Modification Type Group -->
                        <div class="form-group">
                            <label for="mod_type" class="form-label">
                                <i class="fas fa-tag"></i> Tip Modifikacije
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-tags input-icon"></i>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="mod_type" 
                                    name="mod_type" 
                                    required 
                                    placeholder="npr. Turbo Kit, LED Svetla, Sport Suspenzija..."
                                >
                                <i class="fas fa-question-circle input-validator"></i>
                            </div>
                            <div class="form-error" id="mod_type-error"></div>
                            <div class="form-help">Unesite tip ili naziv modifikacije</div>
                        </div>

                        <!-- Category Group -->
                        <div class="form-group">
                            <label for="category" class="form-label">
                                <i class="fas fa-list"></i> Kategorija
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-folder input-icon"></i>
                                <select class="form-control" id="category" name="category" required>
                                    <option value="">-- Odaberi kategoriju --</option>
                                    <option value="Motor">Motor</option>
                                    <option value="Sistem za paljenje">Sistem za paljenje</option>
                                    <option value="Sistem za hladnjenje">Sistem za hladnjenje</option>
                                    <option value="Suspenzija">Suspenzija</option>
                                    <option value="Kočioni sistem">Kočioni sistem</option>
                                    <option value="Enterijeri">Enterijeri</option>
                                    <option value="Eksterijer">Eksterijer</option>
                                    <option value="Osvjetljenje">Osvjetljenje</option>
                                    <option value="Audio/Video">Audio/Video</option>
                                    <option value="Ostalo">Ostalo</option>
                                </select>
                                <i class="fas fa-question-circle input-validator"></i>
                            </div>
                            <div class="form-error" id="category-error"></div>
                        </div>

                        <!-- Description Group -->
                        <div class="form-group">
                            <label for="description" class="form-label">
                                <i class="fas fa-file-alt"></i> Opis Modifikacije
                            </label>
                            <textarea 
                                class="form-control textarea-control" 
                                id="description" 
                                name="description" 
                                rows="4"
                                placeholder="Detaljno objasni šta je modifikovano, koje komponente su korišćene..."
                            ></textarea>
                            <div class="form-error" id="description-error"></div>
                            <div class="form-help">Više detalja pomaže pri evidenciji</div>
                        </div>

                        <!-- Installation Date Group -->
                        <div class="form-group">
                            <label for="installation_date" class="form-label">
                                <i class="fas fa-calendar"></i> Datum Instalacije
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-calendar-alt input-icon"></i>
                                <input 
                                    type="date" 
                                    class="form-control" 
                                    id="installation_date" 
                                    name="installation_date" 
                                    required
                                >
                                <i class="fas fa-question-circle input-validator"></i>
                            </div>
                            <div class="form-error" id="installation_date-error"></div>
                        </div>

                        <!-- Installation Cost Group -->
                        <div class="form-group">
                            <label for="installation_cost" class="form-label">
                                <i class="fas fa-wrench"></i> Cena Instalacije (RSD)
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-money-bill input-icon"></i>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    id="installation_cost" 
                                    name="installation_cost" 
                                    step="0.01"
                                    min="0"
                                    placeholder="npr. 15000"
                                >
                                <i class="fas fa-question-circle input-validator"></i>
                            </div>
                            <div class="form-error" id="installation_cost-error"></div>
                        </div>

                        <!-- Parts Cost Group -->
                        <div class="form-group">
                            <label for="parts_cost" class="form-label">
                                <i class="fas fa-box"></i> Cena Delova (RSD)
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-money-bill input-icon"></i>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    id="parts_cost" 
                                    name="parts_cost" 
                                    step="0.01"
                                    min="0"
                                    placeholder="npr. 45000"
                                >
                                <i class="fas fa-question-circle input-validator"></i>
                            </div>
                            <div class="form-error" id="parts_cost-error"></div>
                        </div>

                        <!-- Total Cost Display (Read-only) -->
                        <div class="form-group">
                            <label for="total_cost" class="form-label">
                                <i class="fas fa-calculator"></i> Ukupna Cena (RSD)
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-sum input-icon"></i>
                                <input 
                                    type="number" 
                                    class="form-control total-cost-display" 
                                    id="total_cost" 
                                    readonly
                                    value="0"
                                    step="0.01"
                                >
                                <i class="fas fa-lock-alt input-validator" style="opacity: 0.5;"></i>
                            </div>
                            <div class="form-help">Automatski izračunata suma</div>
                        </div>

                        <!-- Status Group -->
                        <div class="form-group">
                            <label for="status" class="form-label">
                                <i class="fas fa-tasks"></i> Status
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-spinner input-icon"></i>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="">-- Odaberi status --</option>
                                    <option value="Planirana">Planirana</option>
                                    <option value="U toku">U toku</option>
                                    <option value="Završena" selected>Završena</option>
                                </select>
                                <i class="fas fa-question-circle input-validator"></i>
                            </div>
                            <div class="form-error" id="status-error"></div>
                        </div>

                        <!-- Warranty Group -->
                        <div class="form-group">
                            <label for="warranty" class="form-label">
                                <i class="fas fa-shield-alt"></i> Garantija (meseci)
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-hourglass-end input-icon"></i>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    id="warranty" 
                                    name="warranty" 
                                    min="0"
                                    placeholder="npr. 12"
                                >
                                <i class="fas fa-question-circle input-validator"></i>
                            </div>
                            <div class="form-error" id="warranty-error"></div>
                        </div>

                        <!-- Notes Group -->
                        <div class="form-group">
                            <label for="notes" class="form-label">
                                <i class="fas fa-sticky-note"></i> Napomene
                            </label>
                            <textarea 
                                class="form-control textarea-control" 
                                id="notes" 
                                name="notes" 
                                rows="3"
                                placeholder="Bilo koje dodatne napomene ili specijalne instrukcije..."
                            ></textarea>
                            <div class="form-error" id="notes-error"></div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="reset" class="btn-cancel">
                                <i class="fas fa-redo"></i>
                                <span>Očisti Formu</span>
                            </button>
                            <button type="submit" class="btn-submit">
                                <span>Dodaj Modifikaciju</span>
                                <i class="fas fa-save"></i>
                                <i class="fas fa-spinner fa-spin btn-loader"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Popular Modifications -->
                <div class="popular-modifications fade-in" style="animation-delay: 0.2s;">
                    <h5>
                        <i class="fas fa-star"></i>
                        Česte Modifikacije
                    </h5>
                    <div class="mod-grid">
                        <button type="button" class="mod-btn" data-mod="Turbo Kit">
                            <i class="fas fa-arrow-up"></i>
                            Turbo Kit
                        </button>
                        <button type="button" class="mod-btn" data-mod="LED Sistem">
                            <i class="fas fa-lightbulb"></i>
                            LED Sistem
                        </button>
                        <button type="button" class="mod-btn" data-mod="Sport Suspenzija">
                            <i class="fas fa-arrow-down"></i>
                            Sportska Suspenzija
                        </button>
                        <button type="button" class="mod-btn" data-mod="Sportski Izduv">
                            <i class="fas fa-fan"></i>
                            Sportski Izduv
                        </button>
                        <button type="button" class="mod-btn" data-mod="Kočioni Sistem">
                            <i class="fas fa-hand-paper"></i>
                            Kočioni Sistem
                        </button>
                        <button type="button" class="mod-btn" data-mod="Audio Sistem">
                            <i class="fas fa-music"></i>
                            Audio Sistem
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
<script src="../assets/js/add_modification.js"></script>

</body>
</html>