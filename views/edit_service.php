<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../models/Service.php';

$service = Service::getById($_GET['id'] ?? null);

if (!$service) {
    header('Location: dashboard.php');
    exit;
}

// Verify user owns this service
require_once __DIR__ . '/../models/Car.php';
$car = Car::getById($service['car_id']);

if (!$car || $car['user_id'] != $_SESSION['user']['id']) {
    header('Location: dashboard.php');
    exit;
}

// Get all service types for dropdown
require_once __DIR__ . '/../models/Service.php';
$serviceTypes = Service::getByCar($service['car_id']);

?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Izmeni Servis | Auto Servis</title>
    
    <!-- Favicon -->
    <link rel="icon" href="../assets/img/favicon.png" type="image/png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/edit_service.css">
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
                    <a class="nav-link" href="services.php?car_id=<?php echo htmlspecialchars($car['id']); ?>">Servisi</a>
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
            <h1 class="header-title">Izmeni Servis</h1>
            <p class="header-subtitle">Ažurirajte podatke o servisu vašeg automobila</p>
        </div>
        
        <!-- Progress Indicator -->
        <div class="progress-indicator">
            <div class="progress-step active">
                <div class="step-circle">1</div>
                <span class="step-label">Izmene</span>
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
                
                <!-- Info Card -->
                <div class="info-card fade-in">
                    <div class="info-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="info-content">
                        <h5>Editovanje Servisa</h5>
                        <p>Izmenite sve podatke vezane za obavljen servis. Podaci će biti odmah ažurirani u sistemu.</p>
                    </div>
                </div>

                <!-- Edit Form -->
                <div class="edit-form-container fade-in" style="animation-delay: 0.1s;">
                    <div class="form-header">
                        <i class="fas fa-pen-square"></i>
                        <h3>Podaci Servisa</h3>
                    </div>
                    
                    <form id="editServiceForm" method="POST" action="../controllers/EditController.php">
                        
                        <!-- Hidden Fields -->
                        <input type="hidden" name="type" value="service">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($service['id']); ?>">
                        <input type="hidden" name="car_id" value="<?php echo htmlspecialchars($service['car_id']); ?>">

                        <!-- Car Info Display -->
                        <div class="car-info-display">
                            <div class="info-item">
                                <span class="label">
                                    <i class="fas fa-car"></i>
                                    Vozilo:
                                </span>
                                <span class="value"><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model'] . ' (' . $car['year'] . ')'); ?></span>
                            </div>
                        </div>

                        <!-- Service Type Group -->
                        <div class="form-group">
                            <label for="service_type_id" class="form-label">
                                <i class="fas fa-list"></i> Tip Servisa
                            </label>
                            <div class="input-wrapper select-wrapper">
                                <i class="fas fa-tools input-icon"></i>
                                <select 
                                    class="form-control" 
                                    id="service_type_id" 
                                    name="service_type_id" 
                                    required
                                >
                                    <option value="">Izaberite tip servisa...</option>
                                    <?php foreach ($serviceTypes as $type): ?>
                                        <option 
                                            value="<?php echo $type['id']; ?>"
                                            <?php echo ($service['service_type_id'] == $type['id']) ? 'selected' : ''; ?>
                                        >
                                            <?php echo htmlspecialchars($type['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <i class="fas fa-question-circle input-validator"></i>
                            </div>
                            <div class="form-error" id="service_type_id-error"></div>
                            <div class="form-help">Odaberite tip obavljenog servisa</div>
                        </div>

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
                                    value="<?php echo htmlspecialchars($service['service_date']); ?>"
                                    required
                                >
                                <i class="fas fa-question-circle input-validator"></i>
                            </div>
                            <div class="form-error" id="service_date-error"></div>
                            <div class="form-help">Datum kada je servis obavljen</div>
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
                                placeholder="Detaljno objasni šta je obavljeno..."
                            ><?php echo htmlspecialchars($service['description']); ?></textarea>
                            <div class="form-error" id="description-error"></div>
                            <div class="form-help">Detaljno napišite šta je obavljeno tijekom servisa</div>
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
                                    value="<?php echo htmlspecialchars($service['cost']); ?>"
                                    required
                                    placeholder="npr. 5000"
                                >
                                <i class="fas fa-question-circle input-validator"></i>
                            </div>
                            <div class="form-error" id="cost-error"></div>
                            <div class="form-help">Ukupna cena servisa</div>
                        </div>

                        <!-- Original Cost Display -->
                        <div class="service-info">
                            <div class="info-item">
                                <span class="label">Originalna Cena:</span>
                                <span class="value"><?php echo number_format($service['cost'], 2, ',', '.'); ?> RSD</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Promena Cene:</span>
                                <span class="value price-diff" id="priceDiff">0.00 RSD</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Dani Od Servisa:</span>
                                <span class="value days-ago" id="daysAgo">--</span>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <a href="services.php?car_id=<?php echo htmlspecialchars($car['id']); ?>" class="btn-cancel">
                                <i class="fas fa-times"></i>
                                <span>Otkaži</span>
                            </a>
                            <button type="submit" class="btn-submit">
                                <span>Sačuvaj Izmene</span>
                                <i class="fas fa-save"></i>
                                <i class="fas fa-spinner fa-spin btn-loader"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Delete Option -->
                <div class="delete-section fade-in" style="animation-delay: 0.2s;">
                    <h5>
                        <i class="fas fa-trash-alt"></i>
                        Opasne Akcije
                    </h5>
                    <p>Ako više ne trebate ovaj servis, možete ga obrisati.</p>
                    <form method="POST" action="../controllers/DeleteController.php" onsubmit="return confirm('Da li ste sigurni? Ova akcija se ne može poništiti!');">
                        <input type="hidden" name="type" value="service">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($service['id']); ?>">
                        <input type="hidden" name="car_id" value="<?php echo htmlspecialchars($car['id']); ?>">
                        <button type="submit" class="btn-delete">
                            <i class="fas fa-trash-alt"></i>
                            <span>Obriši Servis</span>
                        </button>
                    </form>
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
<script src="../assets/js/edit_service.js"></script>

</body>
</html>