<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../dashboard.php');
    exit;
}

$users = $users ?? [];
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upravljanje Korisnicima | Auto Servis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/auto-servis/assets/css/style.css">
    <link rel="stylesheet" href="/auto-servis/assets/css/admin.css">
    <link rel="stylesheet" href="/auto-servis/assets/css/users.css">
</head>
<body>
    <!-- Animated Background -->
    <div class="animated-bg">
        <div class="carbon-fiber"></div>
        <div class="gradient-overlay"></div>
    </div>

    <!-- Header -->
    <header class="admin-header">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="brand-section">
                    <i class="fas fa-users-cog brand-icon"></i>
                    <div>
                        <h1 class="brand-title mb-0">Upravljanje Korisnicima</h1>
                        <p class="brand-subtitle mb-0">Pregled i administracija svih korisnika sistema</p>
                    </div>
                </div>
                <div class="user-section">
                    <div class="user-info">
                        <div class="user-avatar">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="user-details">
                            <span class="user-role">Administrator</span>
                            <span class="user-name"><?php echo htmlspecialchars($_SESSION['user']['username']); ?></span>
                        </div>
                    </div>
                    <a href="/auto-servis/views/logout.php" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid px-4 py-4">
        <!-- Page Header -->
        <div class="page-header fade-in">
            <div class="page-header-content">
                <div class="page-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <h2 class="page-title">Korisnici Sistema</h2>
                    <p class="page-subtitle">Ukupno korisnika: <span class="highlight-number"><?php echo count($users); ?></span></p>
                </div>
            </div>
            <div class="page-actions">
                <a href="/auto-servis/admin.php?controller=user&action=dashboard" class="btn-action btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Nazad na Dashboard
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        <?php if (isset($_SESSION['delete_error'])): ?>
            <div class="alert-custom alert-danger fade-in" style="animation-delay: 0.1s;">
                <div class="alert-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="alert-content">
                    <strong>Greška!</strong>
                    <p><?= htmlspecialchars($_SESSION['delete_error']) ?></p>
                </div>
                <button class="alert-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <?php unset($_SESSION['delete_error']); ?>
        <?php endif; ?>

        <!-- Users Grid -->
        <?php if (empty($users)): ?>
            <div class="empty-state fade-in" style="animation-delay: 0.2s;">
                <div class="empty-icon">
                    <i class="fas fa-user-slash"></i>
                </div>
                <h3>Nema Korisnika</h3>
                <p>Trenutno nema registrovanih korisnika u sistemu.</p>
            </div>
        <?php else: ?>
            <!-- Stats Bar -->
            <div class="stats-bar fade-in" style="animation-delay: 0.1s;">
                <div class="stat-item">
                    <i class="fas fa-user"></i>
                    <div>
                        <span class="stat-value"><?php echo count(array_filter($users, fn($u) => $u['role'] === 'user')); ?></span>
                        <span class="stat-label">Korisnici</span>
                    </div>
                </div>
                <div class="stat-item">
                    <i class="fas fa-user-shield"></i>
                    <div>
                        <span class="stat-value"><?php echo count(array_filter($users, fn($u) => $u['role'] === 'admin')); ?></span>
                        <span class="stat-label">Administratori</span>
                    </div>
                </div>
                <div class="stat-item">
                    <i class="fas fa-wrench"></i>
                    <div>
                        <span class="stat-value"><?php echo count(array_filter($users, fn($u) => $u['role'] === 'mechanic')); ?></span>
                        <span class="stat-label">Mehaničari</span>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="users-table-container fade-in" style="animation-delay: 0.2s;">
                <div class="table-header">
                    <h3><i class="fas fa-list me-2"></i>Lista Korisnika</h3>
                    <div class="table-actions">
                        <button class="btn-filter" onclick="toggleFilters()">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                        <button class="btn-export" onclick="exportUsers()">
                            <i class="fas fa-download me-2"></i>Export
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag me-2"></i>ID</th>
                                <th><i class="fas fa-user me-2"></i>Korisničko Ime</th>
                                <th><i class="fas fa-envelope me-2"></i>Email</th>
                                <th><i class="fas fa-shield-alt me-2"></i>Uloga</th>
                                <th class="text-center"><i class="fas fa-cogs me-2"></i>Akcije</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $index => $u): ?>
                                <tr class="user-row" style="animation-delay: <?php echo 0.3 + ($index * 0.05); ?>s;">
                                    <td>
                                        <span class="user-id">#<?php echo str_pad($u['id'], 4, '0', STR_PAD_LEFT); ?></span>
                                    </td>
                                    <td>
                                        <div class="user-info-cell">
                                            <div class="user-avatar-small">
                                                <?php 
                                                $icon = 'fa-user';
                                                if ($u['role'] === 'admin') $icon = 'fa-user-shield';
                                                if ($u['role'] === 'mechanic') $icon = 'fa-user-cog';
                                                ?>
                                                <i class="fas <?php echo $icon; ?>"></i>
                                            </div>
                                            <span class="username"><?php echo htmlspecialchars($u['username']); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="email"><?php echo htmlspecialchars($u['email']); ?></span>
                                    </td>
                                    <td>
                                        <?php 
                                        $roleClass = 'role-user';
                                        $roleIcon = 'fa-user';
                                        $roleText = 'Korisnik';
                                        
                                        if ($u['role'] === 'admin') {
                                            $roleClass = 'role-admin';
                                            $roleIcon = 'fa-crown';
                                            $roleText = 'Administrator';
                                        } elseif ($u['role'] === 'mechanic') {
                                            $roleClass = 'role-mechanic';
                                            $roleIcon = 'fa-wrench';
                                            $roleText = 'Mehaničar';
                                        }
                                        ?>
                                        <span class="role-badge <?php echo $roleClass; ?>">
                                            <i class="fas <?php echo $roleIcon; ?> me-1"></i>
                                            <?php echo $roleText; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="/auto-servis/admin.php?controller=user&action=edit&id=<?php echo $u['id']; ?>" 
                                               class="btn-action-table btn-edit" 
                                               title="Izmeni korisnika">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" 
                                                action="/auto-servis/admin.php?controller=user&action=delete" 
                                                class="d-inline delete-form">
                                                <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
                                                <button type="submit" 
                                                class="btn-action-table btn-delete" 
                                                        title="Obriši korisnika">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="admin-footer">
        <div class="container text-center">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Božidar AutoApp • Admin Control Panel v1.0</p>
        </div>
    </footer>

    <!-- Confirmation Modal -->
<div class="confirm-overlay" id="confirmOverlay">
    <div class="confirm-modal">
        <div class="confirm-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h5>Potvrda Brisanja</h5>
        <p id="confirmMessage">Da li ste sigurni da želite da obrišete ovog korisnika?</p>
        <div class="confirm-actions"> 
            <button class="btn-cancel" onclick="closeConfirmModal()">Otkaži</button> 
            <button id="confirmDelete" class="btn-confirm">Obriši</button> </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/auto-servis/assets/js/main.js"></script>
    <script src="/auto-servis/assets/js/admin.js"></script>
    <script src="/auto-servis/assets/js/users.js"></script>
    <script>
        // Confirmation Dialog
        let pendingForm = null;

        function confirmDelete(username) {
            const overlay = document.getElementById('confirmOverlay');
            const message = document.getElementById('confirmMessage');
            message.textContent = `Da li ste sigurni da želite da obrišete korisnika "${username}"?`;
            
            overlay.classList.add('active');
            setTimeout(() => {
                overlay.querySelector('.confirm-modal').classList.add('active');
            }, 10);
            
            pendingForm = event.target;
            return false;
        }

        function cancelDelete() {
            const overlay = document.getElementById('confirmOverlay');
            overlay.querySelector('.confirm-modal').classList.remove('active');
            setTimeout(() => {
                overlay.classList.remove('active');
            }, 300);
            pendingForm = null;
        }

        function proceedDelete() {
            if (pendingForm) {
                cancelDelete();
                setTimeout(() => {
                    pendingForm.submit();
                }, 300);
            }
        }

        // Filter functionality (placeholder)
        function toggleFilters() {
            alert('Filter funkcionalnost će biti dostupna uskoro!');
        }

        // Export functionality (placeholder)
        function exportUsers() {
            alert('Export funkcionalnost će biti dostupna uskoro!');
        }

        // Close alert
        document.querySelectorAll('.alert-close').forEach(btn => {
            btn.addEventListener('click', function() {
                this.parentElement.style.animation = 'fadeOut 0.3s ease-out forwards';
                setTimeout(() => {
                    this.parentElement.remove();
                }, 300);
            });
        });
    </script>
</body>
</html>