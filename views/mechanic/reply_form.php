<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'mechanic') {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../models/RequestModel.php';

global $pdo;

$request_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$request_id) {
    header("Location: mechanic_dashboard.php");
    exit;
}

// Get request details
$requestModel = new RequestModel($pdo);
$request = $requestModel->getRequestById($request_id);

if (!$request) {
    header("Location: mechanic_dashboard.php");
    exit;
}

// Get user details
require_once __DIR__ . '/../../models/User.php';
$user = User::getById($request['user_id']);
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Odgovor na Zahtev | Auto Servis</title>
    
    <!-- Favicon -->
    <link rel="icon" href="../../assets/img/favicon.png" type="image/png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/auto-servis/assets/css/style.css">
    <link rel="stylesheet" href="/auto-servis/assets/css/reply_form.css">
    <link rel="stylesheet" href="/auto-servis/assets/css/mechanic_header.css">
</head>
<body>

<!-- Animated Background -->
<div class="animated-bg">
    <div class="carbon-fiber"></div>
    <div class="gradient-overlay"></div>
</div>

<!-- Header -->
    <header class="mechanic-header">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="brand-section">
                    <i class="fas fa-tools brand-icon"></i>
                    <div>
                        <h1 class="brand-title mb-0">Mehaničarski Panel</h1>
                        <p class="brand-subtitle mb-0">Upravljanje servisnim zahtevima</p>
                    </div>
                </div>
                <div class="user-section">
                    <div class="user-info">
                        <div class="user-avatar">
                            <i class="fas fa-user-cog"></i>
                        </div>
                        <div class="user-details">
                            <span class="user-role">Mehaničar</span>
                            <span class="user-name"><?= htmlspecialchars($_SESSION['user']['username']) ?></span>
                        </div>
                    </div>
                    <a href="/auto-servis/views/logout.php" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-reply"></i>
            </div>
            <h1 class="header-title">Odgovori na Zahtev</h1>
            <p class="header-subtitle">Prosledite ponudu za servisiranje korisniku</p>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="content-section">
    <div class="container">
        <div class="form-wrapper fade-in">
            
            <!-- Request Info Card - Left Side -->
            <div class="request-info-card">
                <div class="info-header">
                    <h3><i class="fas fa-file-alt"></i> Zahtev Korisnika</h3>
                </div>

                <div class="user-info">
                    <div class="user-item">
                        <span class="user-label">Korisnik:</span>
                        <span class="user-value"><?php echo htmlspecialchars($user['username']); ?></span>
                    </div>
                    <div class="user-item">
                        <span class="user-label">Email:</span>
                        <span class="user-value"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                    <div class="user-item">
                        <span class="user-label">Telefon:</span>
                        <span class="user-value"><?php echo htmlspecialchars($user['phone'] ?? 'Nije navedeno'); ?></span>
                    </div>
                    <div class="user-item">
                        <span class="user-label">Datum zahteva:</span>
                        <span class="user-value"><?php echo date('d.m.Y H:i', strtotime($request['created_at'])); ?></span>
                    </div>
                </div>

                <div class="request-problem">
                    <h4>Opis Problema</h4>
                    <div class="problem-box">
                        <?php echo nl2br(htmlspecialchars($request['description'])); ?>
                    </div>
                </div>
            </div>

            <!-- Reply Form Card - Right Side -->
            <div class="form-card">
                <div class="form-header">
                    <h2>Vaš Odgovor</h2>
                </div>

                <form id="replyForm" method="POST" action="/auto-servis/mechanic.php?action=reply">
                <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">

                    <!-- Proposed Price -->
                    <div class="form-group">
                        <label for="price" class="form-label">
                            <i class="fas fa-money-bill-wave"></i>
                            Predložena Cena (RSD)
                        </label>
                        <div class="input-wrapper">
                            <input 
                                type="number" 
                                name="price" 
                                id="price" 
                                class="form-control form-input" 
                                placeholder="3000.00"
                                step="0.01"
                                min="0"
                                required
                            >
                            <span class="input-suffix">RSD</span>
                        </div>
                    </div>

                    <!-- Proposed Date -->
                    <div class="form-group">
                        <label for="date" class="form-label">
                            <i class="fas fa-calendar-check"></i>
                            Predloženi Datum
                        </label>
                        <input 
                            type="date" 
                            name="date" 
                            id="date" 
                            class="form-control form-input" 
                            required
                        >
                    </div>

                    <!-- Mechanic Note -->
                    <div class="form-group">
                        <label for="note" class="form-label">
                            <i class="fas fa-sticky-note"></i>
                            Napomena
                        </label>
                        <textarea 
                            name="note" 
                            id="note" 
                            class="form-control form-textarea" 
                            rows="3"
                            placeholder="Npr. Dodjite na pregled..."
                            required
                        ></textarea>
                        <div class="char-count">
                            <span id="charCounter">0</span> / 500
                        </div>
                    </div>

                    <!-- Accept Terms -->
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="accept_terms" required>
                            <span>Potvrđujem da je ponuda tačna</span>
                        </label>
                    </div>

                   <div class="form-actions">
                  <!-- Cancel link -->
                <a href="/auto-servis/mechanic.php?action=dashboard" class="btn-back">
                  <i class="fas fa-arrow-left"></i>
                    Otkaži
                </a>

                  <!-- Submit button -->
                <button type="submit" class="btn-submit">
                      <i class="fas fa-paper-plane"></i>
                     Pošalji Odgovor
                    </button>
              </div>

                </form>
            </div>

            <!-- Preview Sidebar -->
            <div class="form-preview">
                <div class="preview-header">
                    <h3><i class="fas fa-eye"></i> Pregled</h3>
                </div>
                <div class="preview-content">
                    <div class="preview-section">
                        <h4>Vidljivo Korisniku</h4>
                        
                        <div class="preview-item">
                            <span class="preview-label">Mehaničar</span>
                            <span class="preview-value" id="previewMechanic"><?php echo htmlspecialchars($_SESSION['user']['username']); ?></span>
                        </div>

                        <div class="preview-divider"></div>

                        <div class="preview-item">
                            <span class="preview-label">Opis Problema</span>
                            <span class="preview-value preview-description"><?php echo substr(htmlspecialchars($request['description']), 0, 80); ?>...</span>
                        </div>

                        <div class="preview-item">
                            <span class="preview-label">Predložena Cena</span>
                            <span class="preview-value" id="previewPrice">-</span>
                        </div>

                        <div class="preview-item">
                            <span class="preview-label">Predloženi Datum</span>
                            <span class="preview-value" id="previewDate">-</span>
                        </div>

                        <div class="preview-item">
                            <span class="preview-label">Napomena</span>
                            <span class="preview-value preview-description" id="previewNote">-</span>
                        </div>

                        <div class="preview-status-box">
                            <span class="preview-status" id="previewStatus">Nepotpuno</span>
                        </div>
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
<script src="/auto-servis/assets/js/main.js"></script>
<script src="/auto-servis/assets/js/reply_form.js" defer></script>

</body>
</html>