<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../models/Modification.php';

$mod = Modification::getById($_GET['id'] ?? null);

if (!$mod) {
    header('Location: dashboard.php');
    exit;
}


?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Izmeni Modifikaciju | Auto Servis</title>
    
    <!-- Favicon -->
    <link rel="icon" href="../assets/img/favicon.png" type="image/png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/edit_modifications.css">
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
                    <a class="nav-link" href="modifications.php?car_id=<?php echo htmlspecialchars($mod['car_id']); ?>">Modifikacije</a>
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
                <i class="fas fa-edit"></i>
            </div>
            <h1 class="header-title">Izmeni Modifikaciju</h1>
            <p class="header-subtitle">Ažurirajte podatke o modifikaciji vašeg automobila</p>
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
                        <h5>Editovanje Modifikacije</h5>
                        <p>Izmenite podatke o modifikaciji. Svi podaci će biti ažurirani u sistemu.</p>
                    </div>
                </div>

                <!-- Edit Form -->
                <div class="edit-form-container fade-in" style="animation-delay: 0.1s;">
                    <div class="form-header">
                        <i class="fas fa-pen-square"></i>
                        <h3>Podaci Modifikacije</h3>
                    </div>
                    
                    <form id="editModificationForm" method="POST" action="../controllers/EditController.php">
                        
                        <!-- Hidden Fields -->
                        <input type="hidden" name="type" value="modification">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($mod['id']); ?>">
                        <input type="hidden" name="car_id" value="<?php echo htmlspecialchars($mod['car_id']); ?>">

                        <!-- Modification Date Group -->
                        <div class="form-group">
                            <label for="mod_date" class="form-label">
                                <i class="fas fa-calendar"></i> Datum Modifikacije
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-calendar-alt input-icon"></i>
                                <input 
                                    type="date" 
                                    class="form-control" 
                                    id="mod_date" 
                                    name="mod_date" 
                                    value="<?php echo htmlspecialchars($mod['mod_date']); ?>"
                                    required
                                >
                                <i class="fas fa-question-circle input-validator"></i>
                            </div>
                            <div class="form-error" id="mod_date-error"></div>
                            <div class="form-help">Datum kada je obavljena modifikacija</div>
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
                                required
                                placeholder="Detaljno objasni modifikaciju..."
                            ><?php echo htmlspecialchars($mod['description']); ?></textarea>
                            <div class="form-error" id="description-error"></div>
                            <div class="form-help">Detaljno napišite šta je modifikovano</div>
                        </div>

                        <!-- Cost Group -->
                        <div class="form-group">
                            <label for="cost" class="form-label">
                                <i class="fas fa-money-bill"></i> Cena Modifikacije (RSD)
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
                                    value="<?php echo htmlspecialchars($mod['cost']); ?>"
                                    required
                                    placeholder="npr. 15000"
                                >
                                <i class="fas fa-question-circle input-validator"></i>
                            </div>
                            <div class="form-error" id="cost-error"></div>
                            <div class="form-help">Ukupna cena modifikacije</div>
                        </div>

                        <!-- Original Cost Display -->
                        <div class="original-info">
                            <div class="info-item">
                                <span class="label">Originalna Cena:</span>
                                <span class="value"><?php echo number_format($mod['cost'], 2, ',', '.'); ?> RSD</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Promena Cene:</span>
                                <span class="value price-diff" id="priceDiff">0.00 RSD</span>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <a href="modifications.php?car_id=<?php echo htmlspecialchars($mod['car_id']); ?>" class="btn-cancel">
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
                    <p>Ako više ne trebate ovu modifikaciju, možete je obrisati.</p>
                    <form method="POST" action="../controllers/DeleteController.php" onsubmit="return confirm('Da li ste sigurni? Ova akcija se ne može poništiti!');">
                        <input type="hidden" name="type" value="modification">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($mod['id']); ?>">
                        <input type="hidden" name="car_id" value="<?php echo htmlspecialchars($mod['car_id']); ?>">
                        <button type="submit" class="btn-delete">
                            <i class="fas fa-trash-alt"></i>
                            <span>Obriši Modifikaciju</span>
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
<script src="../assets/js/edit_modifications.js"></script>

</body>
</html>