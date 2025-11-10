<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../dashboard.php');
    exit;
}

$user = $user ?? null;
$roles = $roles ?? [];
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Izmena korisnika | Auto Servis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Favicon -->
    <link rel="icon" href="/auto-servis/assets/img/favicon.png" type="image/png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/auto-servis/assets/css/edit_user.css">
</head>
<body>

<!-- Animated Background -->
<div class="animated-bg"></div>

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
                    <a class="nav-link" href="/auto-servis/admin.php?controller=user&action=dashboard">
                        <i class="fas fa-tachometer-alt me-1"></i>Admin Panel
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/auto-servis/admin.php?controller=user&action=index">
                        <i class="fas fa-users me-1"></i>Korisnici
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/auto-servis/views/logout.php">
                        <i class="fas fa-sign-out-alt me-1"></i>Odjava
                    </a>
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
                <i class="fas fa-user-edit"></i>
            </div>
            <h1 class="header-title">Izmena Korisnika</h1>
            <p class="header-subtitle">Ažurirajte podatke i privilegije korisničkog naloga</p>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="content-section">
    <div class="container">
        <?php if (!$user || !is_array($user)): ?>
            <!-- Error State -->
            <div class="error-container">
                <div class="error-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h2 class="error-title">Korisnik nije pronađen</h2>
                <p class="error-text">Traženi korisnik ne postoji ili podaci nisu dostupni u sistemu.</p>
                <a href="/auto-servis/admin.php?controller=user&action=index" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Nazad na listu korisnika
                </a>
            </div>
        <?php else: ?>
            <!-- Edit Form -->
            <div class="form-container">
                <!-- User Info Card -->
                <div class="user-info-card">
                    <div class="user-avatar-large">
                        <?php echo strtoupper(substr($user['username'], 0, 2)); ?>
                    </div>
                    <div class="user-info">
                        <h3><?php echo htmlspecialchars($user['username']); ?></h3>
                        <p class="user-email">
                            <i class="fas fa-envelope me-2"></i>
                            <?php echo htmlspecialchars($user['email']); ?>
                        </p>
                        <span class="user-role-badge role-<?php echo $user['role']; ?>">
                            <i class="fas fa-shield-alt me-1"></i>
                            <?php echo ucfirst($user['role']); ?>
                        </span>
                    </div>
                </div>

                <!-- Edit Form -->
                <form action="/auto-servis/admin.php?controller=user&action=update" method="POST" id="editUserForm" class="edit-form">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">

                    <div class="form-grid">
                        <!-- Username Field -->
                        <div class="form-group">
                            <label for="username" class="form-label">
                                <i class="fas fa-user me-2"></i>Korisničko ime
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" 
                                       class="form-control" 
                                       id="username" 
                                       name="username"
                                       value="<?php echo htmlspecialchars($user['username']); ?>" 
                                       required
                                       minlength="3"
                                       maxlength="50"
                                       placeholder="Unesite korisničko ime">
                            </div>
                            <div class="form-help">Minimum 3 karaktera, maksimum 50</div>
                        </div>

                        <!-- Email Field -->
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>E-mail adresa
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email"
                                       value="<?php echo htmlspecialchars($user['email']); ?>" 
                                       required
                                       placeholder="korisnik@example.com">
                            </div>
                            <div class="form-help">Mora biti validna e-mail adresa</div>
                        </div>

                        <!-- Role Field -->
                        <div class="form-group">
                            <label for="role" class="form-label">
                                <i class="fas fa-shield-alt me-2"></i>Korisnička uloga
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </span>
                                <select name="role" id="role" class="form-control form-select" required>
                                    <option value="" disabled>Izaberite ulogu</option>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?php echo $role; ?>" 
                                                <?php echo ($user['role'] === $role) ? 'selected' : ''; ?>>
                                            <?php 
                                            $roleLabels = [
                                                'admin' => '👑 Administrator',
                                                'mechanic' => '🔧 Mehaničar',
                                                'user' => '👤 Korisnik'
                                            ];
                                            echo $roleLabels[$role] ?? ucfirst($role);
                                            ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-help">Određuje nivo pristupa korisniku</div>
                        </div>

                        <!-- Password Reset Info -->
                        <div class="form-group full-width">
                            <div class="info-box">
                                <i class="fas fa-info-circle"></i>
                                <div>
                                    <strong>Napomena o lozinci:</strong>
                                    <p>Za promenu lozinke korisnika, koristite posebnu opciju za resetovanje lozinke u admin panelu.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save me-2"></i>
                            Sačuvaj izmene
                        </button>
                        <button type="reset" class="btn-reset">
                            <i class="fas fa-undo me-2"></i>
                            Resetuj
                        </button>
                        <a href="/auto-servis/admin.php?controller=user&action=dashboard" class="btn-cancel">
                            <i class="fas fa-times me-2"></i>
                            Otkaži
                        </a>
                    </div>
                </form>

                <!-- Audit Trail (Optional) -->
                <div class="audit-trail">
                    <h4><i class="fas fa-history me-2"></i>Informacije o nalogu</h4>
                    <div class="audit-info">
                        <div class="audit-item">
                            <span class="audit-label">ID korisnika:</span>
                            <span class="audit-value">#<?php echo htmlspecialchars($user['id']); ?></span>
                        </div>
                        <div class="audit-item">
                            <span class="audit-label">Datum kreiranja:</span>
                            <span class="audit-value">
                                <?php 
                                if (isset($user['created_at'])) {
                                    echo date('d.m.Y H:i', strtotime($user['created_at']));
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </span>
                        </div>
                        <div class="audit-item">
                            <span class="audit-label">Status naloga:</span>
                            <span class="audit-value">
                                <span class="status-badge status-active">
                                    <i class="fas fa-check-circle me-1"></i>Aktivan
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h5><i class="fas fa-car-side me-2"></i>Auto Servis</h5>
                <p class="text-muted">Premium platforma za digitalno upravljanje servisima.</p>
            </div>
            <div class="footer-section">
                <h5>Admin Panel</h5>
                <ul>
                    <li><a href="/auto-servis/admin.php?controller=user&action=dashboard">Dashboard</a></li>
                    <li><a href="/auto-servis/admin.php?controller=user&action=index">Upravljanje korisnicima</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Božidar AutoApp | Sva prava zadržana</p>
        </div>
    </div>
</footer>

<!-- Scroll to top button -->
<div class="scroll-top" id="scrollTop">
    <i class="fas fa-arrow-up"></i>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JavaScript -->
<script src="/auto-servis/assets/js/edit_user.js"></script>

</body>
</html>