<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$carId = $_GET['car_id'] ?? null;
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
    <title>Dodaj Podsetnik | Auto Servis</title>
    
    <!-- Favicon -->
    <link rel="icon" href="../assets/img/favicon.png" type="image/png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/add_reminder.css">
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
                    <a class="nav-link" href="reminders.php">Moji Podsetnici</a>
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
            <h1 class="header-title">Dodaj Novi Podsetnik</h1>
            <p class="header-subtitle">Postavi podsetnik za važne službe na tvom automobilu</p>
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
                
                <!-- Info Card -->
                <div class="info-card fade-in">
                    <div class="info-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <div class="info-content">
                        <h5>Savet:</h5>
                        <p>Postavi podsetnik za važna održavanja automobila kako ne bi zaboravio na servis, pregled ili druge važne poslove.</p>
                    </div>
                </div>

                <!-- Reminder Form -->
                <div class="reminder-form-container fade-in" style="animation-delay: 0.1s;">
                    <div class="form-header">
                        <i class="fas fa-clipboard-list"></i>
                        <h3>Osnovni Podaci Podsetnika</h3>
                    </div>
                    
                    <form id="addReminderForm" method="POST" action="../controllers/AddReminderController.php">
                        
                        <!-- Hidden Car ID -->
                        <input type="hidden" name="car_id" value="<?php echo htmlspecialchars($carId); ?>">

                        <!-- Reminder Type Group -->
                        <div class="form-group">
                            <label for="reminder_type" class="form-label">
                                <i class="fas fa-tag"></i> Tip Podsetnika
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-tags input-icon"></i>
                                <select class="form-control" id="reminder_type" name="reminder_type" required>
                                    <option value="">-- Odaberi tip podsetnika --</option>
                                    <option value="Redovan servis">Redovan servis</option>
                                    <option value="Zamena ulja">Zamena ulja</option>
                                    <option value="Pregled guma">Pregled guma</option>
                                    <option value="Zamena filtera">Zamena filtera</option>
                                    <option value="Servis kočnica">Servis kočnica</option>
                                    <option value="Pregled suspenzije">Pregled suspenzije</option>
                                    <option value="Tehnički pregled">Tehnički pregled</option>
                                    <option value="Osiguranje">Osiguranje</option>
                                    <option value="Registracija">Registracija</option>
                                    <option value="Ostalo">Ostalo</option>
                                </select>
                                <i class="fas fa-question-circle input-validator"></i>
                            </div>
                            <div class="form-error" id="reminder_type-error"></div>
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
                                    required
                                >
                                <i class="fas fa-question-circle input-validator"></i>
                            </div>
                            <div class="form-error" id="reminder_date-error"></div>
                            <div class="form-help">Odaberi datum kada želiš da budeš podsećen</div>
                        </div>

                        <!-- Reminder Time Group (Optional) -->
                        <div class="form-group">
                            <label for="reminder_time" class="form-label">
                                <i class="fas fa-clock"></i> Vreme Podsetnika (opciono)
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-hourglass-start input-icon"></i>
                                <input 
                                    type="time" 
                                    class="form-control" 
                                    id="reminder_time" 
                                    name="reminder_time"
                                >
                                <i class="fas fa-question-circle input-validator"></i>
                            </div>
                            <div class="form-help">Postavi vreme kada želiš obaveštenje</div>
                        </div>

                        <!-- Priority Group -->
                        <div class="form-group">
                            <label for="priority" class="form-label">
                                <i class="fas fa-exclamation-triangle"></i> Prioritet
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-signal input-icon"></i>
                                <select class="form-control" id="priority" name="priority">
                                    <option value="Normalna">Normalna</option>
                                    <option value="Važna">Važna</option>
                                    <option value="Hitna">Hitna</option>
                                </select>
                                <i class="fas fa-question-circle input-validator"></i>
                            </div>
                            <div class="form-help">Postavi prioritet za ovaj podsetnik</div>
                        </div>

                        <!-- Note/Description Group -->
                        <div class="form-group">
                            <label for="note" class="form-label">
                                <i class="fas fa-sticky-note"></i> Napomena / Opis
                            </label>
                            <textarea 
                                class="form-control textarea-control" 
                                id="note" 
                                name="note" 
                                rows="4"
                                required
                                placeholder="Detaljno objasni šta treba da se uradi, где и kada..."
                            ></textarea>
                            <div class="form-error" id="note-error"></div>
                            <div class="form-help">Što detaljnija napomena, to je bolje</div>
                        </div>

                        <!-- Cost Estimate Group (Optional) -->
                        <div class="form-group">
                            <label for="estimated_cost" class="form-label">
                                <i class="fas fa-money-bill"></i> Procenjena Cena (RSD)
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-coins input-icon"></i>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    id="estimated_cost" 
                                    name="estimated_cost" 
                                    step="0.01"
                                    min="0"
                                    placeholder="npr. 5000"
                                >
                                <i class="fas fa-question-circle input-validator"></i>
                            </div>
                            <div class="form-error" id="estimated_cost-error"></div>
                            <div class="form-help">Proceni cenu servisa (opciono)</div>
                        </div>

                        <!-- Status Group -->
                        <div class="form-group">
                            <label for="status" class="form-label">
                                <i class="fas fa-tasks"></i> Status
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-check-circle input-icon"></i>
                                <select class="form-control" id="status" name="status">
                                    <option value="Aktivna">Aktivna</option>
                                    <option value="Dovršena">Dovršena</option>
                                    <option value="Na čekanju">Na čekanju</option>
                                </select>
                                <i class="fas fa-question-circle input-validator"></i>
                            </div>
                            <div class="form-help">Postavi status podsetnika</div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="reset" class="btn-cancel">
                                <i class="fas fa-redo"></i>
                                <span>Očisti Formu</span>
                            </button>
                            <button type="submit" class="btn-submit">
                                <span>Dodaj Podsetnik</span>
                                <i class="fas fa-save"></i>
                                <i class="fas fa-spinner fa-spin btn-loader"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Popular Reminders -->
                <div class="popular-reminders fade-in" style="animation-delay: 0.2s;">
                    <h5>
                        <i class="fas fa-star"></i>
                        Česti Podsetnici
                    </h5>
                    <div class="reminder-grid">
                        <button type="button" class="reminder-btn" data-type="Redovan servis">
                            <i class="fas fa-tools"></i>
                            Redovan Servis
                        </button>
                        <button type="button" class="reminder-btn" data-type="Zamena ulja">
                            <i class="fas fa-oil-can"></i>
                            Zamena Ulja
                        </button>
                        <button type="button" class="reminder-btn" data-type="Pregled guma">
                            <i class="fas fa-tire"></i>
                            Pregled Guma
                        </button>
                        <button type="button" class="reminder-btn" data-type="Tehnički pregled">
                            <i class="fas fa-microscope"></i>
                            Tehnički Pregled
                        </button>
                        <button type="button" class="reminder-btn" data-type="Osiguranje">
                            <i class="fas fa-shield-alt"></i>
                            Osiguranje
                        </button>
                        <button type="button" class="reminder-btn" data-type="Registracija">
                            <i class="fas fa-id-card"></i>
                            Registracija
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
<script src="../assets/js/add_reminder.js"></script>

</body>
</html>