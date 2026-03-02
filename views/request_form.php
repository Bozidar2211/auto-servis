<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: /auto-servis/login.php");  
    exit;
}

require_once __DIR__ . '/../models/Car.php';
require_once __DIR__ . '/../models/User.php';

$cars = Car::getByUser($_SESSION['user']['id']);
$allUsers = User::getAll();
$mechanics = array_filter($allUsers, function($u) {
    return $u['role'] === 'mechanic';
});

?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novi Servisni Zahtev | Auto Servis</title>
    
    <!-- Favicon -->
    <link rel="icon" href="/auto-servis/assets/img/favicon.png" type="image/png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/auto-servis/assets/css/style.css">
    <link rel="stylesheet" href="/auto-servis/assets/css/request_form.css">
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
                    <a class="nav-link" href="/auto-servis/views/dashboard.php">  
                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/auto-servis/user.php?controller=request&action=myRequests">  
                        <i class="fa-regular fa-envelope me-1"></i> Moji Zahtevi
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
                <i class="fas fa-wrench"></i>
            </div>
            <h1 class="header-title">Novi Servisni Zahtev</h1>
            <p class="header-subtitle">Popunite formu da biste zahtevali servisiranje vašeg automobila</p>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="content-section">
    <div class="container">
        <div class="form-wrapper fade-in">
            
            <!-- Progress Steps -->
            <div class="progress-steps">
                <div class="step active" data-step="1">
                    <div class="step-number">1</div>
                    <div class="step-label">Vozilo</div>
                </div>
                <div class="step-line"></div>
                <div class="step" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-label">Mehaničar</div>
                </div>
                <div class="step-line"></div>
                <div class="step" data-step="3">
                    <div class="step-number">3</div>
                    <div class="step-label">Opis</div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="form-card">
                <form id="requestForm" method="POST" action="/auto-servis/user.php?controller=request&action=submit">
                    
                    <!-- Step 1: Select Car -->
                    <fieldset class="form-section active" data-section="1">
                        <div class="section-header">
                            <h2>Odaberite vozilo</h2>
                            <p>Koji automobil zahteva servisiranje?</p>
                        </div>

                        <?php if (empty($cars)): ?>
                            <div class="empty-message">
                                <i class="fas fa-car"></i>
                                <p>Nema registrovanih vozila.</p>
                                <a href="add_car.php" class="btn-add-car">
                                    <i class="fas fa-plus"></i>
                                    Dodaj vozilo
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="cars-grid">
                                <?php foreach ($cars as $car): ?>
                                    <label class="car-option">
                                        <input type="radio" name="car_id" value="<?php echo $car['id']; ?>" required>
                                        <div class="car-card">
                                            <div class="car-icon">
                                                <i class="fas fa-car"></i>
                                            </div>
                                            <div class="car-info">
                                                <h3><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h3>
                                                <p><?php echo htmlspecialchars($car['year']); ?></p>
                                                <span class="registration"><?php echo htmlspecialchars($car['registration']); ?></span>
                                            </div>
                                            <div class="car-check">
                                                <i class="fas fa-check"></i>
                                            </div>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </fieldset>

                    <!-- Step 2: Select Mechanic -->
                    <fieldset class="form-section" data-section="2">
                        <div class="section-header">
                            <h2>Odaberite mehaničara</h2>
                            <p>Kojeg mehaničara želite?</p>
                        </div>

                        <div class="mechanics-grid">
                            <?php foreach ($mechanics as $mechanic): ?>
                                <label class="mechanic-option">
                                    <input type="radio" name="mechanic_id" value="<?php echo $mechanic['id']; ?>" required>
                                    <div class="mechanic-card">
                                        <div class="mechanic-avatar">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div class="mechanic-info">
                                            <h3><?php echo htmlspecialchars($mechanic['username']); ?></h3>
                                            <p><?php echo htmlspecialchars($mechanic['email']); ?></p>
                                            <div class="rating">
                                                <i class="fas fa-star"></i>
                                                <span>4.8</span>
                                            </div>
                                        </div>
                                        <div class="mechanic-check">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>

                    <!-- Step 3: Problem Description -->
                    <fieldset class="form-section" data-section="3">
                        <div class="section-header">
                            <h2>Opišite problem</h2>
                            <p>Što vas brine na automobilu?</p>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">
                                <i class="fas fa-file-alt"></i>
                                Detaljno objašnjenje problema
                            </label>
                            <textarea 
                                name="description" 
                                id="description" 
                                class="form-control form-textarea" 
                                rows="6" 
                                placeholder="Detaljno opišite šta vas brine na automobilu..."
                                required
                            ></textarea>
                            <div class="char-count">
                                <span id="charCounter">0</span> / 500
                            </div>
                        </div>

                        <div class="problem-categories">
                            <label class="category-checkbox">
                                <input type="checkbox" name="problem_type" value="mehanicki">
                                <span>Mehanički problemi</span>
                            </label>
                            <label class="category-checkbox">
                                <input type="checkbox" name="problem_type" value="elektricni">
                                <span>Električni problemi</span>
                            </label>
                            <label class="category-checkbox">
                                <input type="checkbox" name="problem_type" value="voznnja">
                                <span>Problemi sa vožnjom</span>
                            </label>
                            <label class="category-checkbox">
                                <input type="checkbox" name="problem_type" value="konsumables">
                                <span>Zamena konzumabilnih delova</span>
                            </label>
                            <label class="category-checkbox">
                                <input type="checkbox" name="problem_type" value="redovno">
                                <span>Redovno održavanje</span>
                            </label>
                        </div>

                        <div class="urgency-selector">
                            <label class="form-label">
                                <i class="fas fa-exclamation-circle"></i>
                                Hitnost zahteva
                            </label>
                            <div class="urgency-options">
                                <label class="urgency-option">
                                    <input type="radio" name="urgency" value="low">
                                    <span class="urgency-label low">
                                        <i class="fas fa-circle"></i>
                                        Nizak prioritet
                                    </span>
                                </label>
                                <label class="urgency-option">
                                    <input type="radio" name="urgency" value="medium" checked>
                                    <span class="urgency-label medium">
                                        <i class="fas fa-circle"></i>
                                        Srednji prioritet
                                    </span>
                                </label>
                                <label class="urgency-option">
                                    <input type="radio" name="urgency" value="high">
                                    <span class="urgency-label high">
                                        <i class="fas fa-circle"></i>
                                        Visoki prioritet
                                    </span>
                                </label>
                            </div>
                        </div>
                    </fieldset>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="button" class="btn-back" id="prevBtn" style="display: none;">
                            <i class="fas fa-chevron-left"></i>
                            Nazad
                        </button>
                        <button type="button" class="btn-next" id="nextBtn">
                            Sledeće
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        <button type="submit" class="btn-submit" id="submitBtn" style="display: none;">
                            <i class="fas fa-paper-plane"></i>
                            Pošalji zahtev
                        </button>
                    </div>
                </form>
            </div>

            <!-- Form Preview -->
            <div class="form-preview">
                <div class="preview-header">
                    <h3>Pregled zahteva</h3>
                </div>
                <div class="preview-content">
                    <div class="preview-item">
                        <span class="preview-label">Vozilo</span>
                        <span class="preview-value" id="previewCar">Nije odabrano</span>
                    </div>
                    <div class="preview-item">
                        <span class="preview-label">Mehaničar</span>
                        <span class="preview-value" id="previewMechanic">Nije odabrano</span>
                    </div>
                    <div class="preview-item">
                        <span class="preview-label">Hitnost</span>
                        <span class="preview-value" id="previewUrgency">Nije odabrano</span>
                    </div>
                    <div class="preview-item">
                        <span class="preview-label">Opis</span>
                        <span class="preview-value" id="previewDescription">-</span>
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
<script src="/auto-servis/assets/js/request_form.js"></script>

</body>
</html>