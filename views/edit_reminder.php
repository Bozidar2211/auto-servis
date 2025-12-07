<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../models/Reminder.php';

$reminder = Reminder::getById($_GET['id'] ?? null);

if (!$reminder) {
    header('Location: dashboard.php');
    exit;
}

// Verify user owns this reminder
require_once __DIR__ . '/../models/Car.php';
$car = Car::getById($reminder['car_id']);

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
    <title>Izmeni Podsetnik | Auto Servis</title>
    
    <!-- Favicon -->
    <link rel="icon" href="../assets/img/favicon.png" type="image/png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/edit_reminder.css">
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
                    <a class="nav-link" href="reminders.php?car_id=<?php echo htmlspecialchars($car['id']); ?>">Podsetnici</a>
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
                <i class="fas fa-bell"></i>
            </div>
            <h1 class="header-title">Izmeni Podsetnik</h1>
            <p class="header-subtitle">Ažurirajte podatke o vašem podsetniku</p>
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
                        <h5>Editovanje Podsetnika</h5>
                        <p>Izmenite datum i napomenu vašeg podsetnika. Sistem će vas obavestiti u zadatom vremenu.</p>
                    </div>
                </div>

                <!-- Edit Form -->
                <div class="edit-form-container fade-in" style="animation-delay: 0.1s;">
                    <div class="form-header">
                        <i class="fas fa-pen-square"></i>
                        <h3>Podaci Podsetnika</h3>
                    </div>
                    
                    <form id="editReminderForm" method="POST" action="../controllers/EditController.php">
                        
                        <!-- Hidden Fields -->
                        <input type="hidden" name="type" value="reminder">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($reminder['id']); ?>">
                        <input type="hidden" name="car_id" value="<?php echo htmlspecialchars($reminder['car_id']); ?>">

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

                        <!-- Reminder Date Group -->
                        <div class="form-group">
                            <label for="reminder_date" class="form-label">
                                <i class="fas fa-calendar"></i> Datum Podsetnika
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-calendar-alt input-icon"></i>
                                <input 
                                    type="date" 
                                    class="form-control" 
                                    id="reminder_date" 
                                    name="reminder_date" 
                                    value="<?php echo htmlspecialchars($reminder['reminder_date']); ?>"
                                    required
                                >
                                <i class="fas fa-question-circle input-validator"></i>
                            </div>
                            <div class="form-error" id="reminder_date-error"></div>
                            <div class="form-help">Datum kada želite da budete podseteni</div>
                        </div>

                        <!-- Note Group -->
                        <div class="form-group">
                            <label for="note" class="form-label">
                                <i class="fas fa-sticky-note"></i> Napomena
                            </label>
                            <textarea 
                                class="form-control textarea-control" 
                                id="note" 
                                name="note" 
                                rows="4"
                                required
                                placeholder="Napomena za podsetnik..."
                            ><?php echo htmlspecialchars($reminder['note']); ?></textarea>
                            <div class="form-error" id="note-error"></div>
                            <div class="form-help">Što želite da vas podsetimo?</div>
                            <div class="character-count">
                                <span id="charCount">0</span> / 200
                            </div>
                        </div>

                        <!-- Days Until Reminder Display -->
                        <div class="reminder-info">
                            <div class="info-item">
                                <span class="label">Datum Podsetnika:</span>
                                <span class="value date-display" id="dateDisplay"><?php echo date('d.m.Y', strtotime($reminder['reminder_date'])); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Dana Do Podsetnika:</span>
                                <span class="value days-diff" id="daysDiff">--</span>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <a href="reminders.php?car_id=<?php echo htmlspecialchars($car['id']); ?>" class="btn-cancel">
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
                    <p>Ako više ne trebate ovaj podsetnik, možete ga obrisati.</p>
                    <form method="POST" action="../controllers/DeleteController.php" onsubmit="return confirm('Da li ste sigurni? Ova akcija se ne može poništiti!');">
                        <input type="hidden" name="type" value="reminder">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($reminder['id']); ?>">
                        <input type="hidden" name="car_id" value="<?php echo htmlspecialchars($car['id']); ?>">
                        <button type="submit" class="btn-delete">
                            <i class="fas fa-trash-alt"></i>
                            <span>Obriši Podsetnik</span>
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
<script src="../assets/js/edit_reminder.js"></script>

</body>
</html>