<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'mechanic') {
    header("Location: ../../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mehaničarski Panel | Auto Servis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/auto-servis/assets/css/style.css">
    <link rel="stylesheet" href="/auto-servis/assets/css/mechanic.css">
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

    <div class="container-fluid px-4 py-4">
        <!-- Stats Cards -->
        <div class="stats-grid fade-in">
            <div class="stat-card stat-total">
                <div class="stat-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Ukupno zahteva</span>
                    <span class="stat-number" data-count="<?= $total ?>"><?= $total ?></span>
                </div>
            </div>

            <div class="stat-card stat-pending">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Na čekanju</span>
                    <span class="stat-number" data-count="<?= $pending ?>"><?= $pending ?></span>
                </div>
            </div>

            <div class="stat-card stat-answered">
                <div class="stat-icon">
                    <i class="fas fa-reply"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Odgovoreno</span>
                    <span class="stat-number" data-count="<?= $answered ?>"><?= $answered ?></span>
                </div>
            </div>

            <div class="stat-card stat-scheduled">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Zakazano</span>
                    <span class="stat-number" data-count="<?= $scheduled ?>"><?= $scheduled ?></span>
                </div>
            </div>

            <div class="stat-card stat-completed">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Završeno</span>
                    <span class="stat-number" data-count="<?= $completed ?>"><?= $completed ?></span>
                </div>
            </div>
        </div>

        <!-- Requests Section -->
        <div class="requests-section fade-in" style="animation-delay: 0.2s;">
            <div class="section-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-inbox"></i>
                    <h3 class="mb-0">Pristigli Zahtevi</h3>
                </div>
                <div class="filter-buttons">
                    <a href="/auto-servis/mechanic.php?controller=mechanic&action=dashboard&filter=active" 
                       class="btn-filter <?= ($_GET['filter'] ?? 'active') === 'active' ? 'active' : '' ?>">
                        <i class="fas fa-exclamation-circle me-1"></i>Aktivni
                    </a>
                    <a href="/auto-servis/mechanic.php?controller=mechanic&action=dashboard&filter=all" 
                       class="btn-filter <?= ($_GET['filter'] ?? 'active') === 'all' ? 'active' : '' ?>">
                        <i class="fas fa-list me-1"></i>Svi
                    </a>
                    <button class="btn-refresh" onclick="location.reload()">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>

            <!-- Requests Table -->
            <div class="requests-table-wrapper">
                <?php if (empty($requests)): ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h4>Nema zahteva</h4>
                        <p>Trenutno nemate pristiglih zahteva</p>
                    </div>
                <?php else: ?>
                    <table class="requests-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-user me-2"></i>Korisnik</th>
                                <th><i class="fas fa-car me-2"></i>Vozilo</th>
                                <th><i class="fas fa-comment me-2"></i>Opis</th>
                                <th><i class="fas fa-info-circle me-2"></i>Status</th>
                                <th class="text-center"><i class="fas fa-cog me-2"></i>Akcija</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requests as $index => $r): ?>
                                <tr class="request-row" style="animation-delay: <?= $index * 0.05 ?>s;">
                                    <td>
                                        <div class="user-cell">
                                            <div class="user-avatar-small">
                                                <?= strtoupper(substr($r['user_name'], 0, 1)) ?>
                                            </div>
                                            <span><?= htmlspecialchars($r['user_name']) ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="car-cell">
                                            <i class="fas fa-car"></i>
                                            <span><?= htmlspecialchars($r['car_model']) ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="description-cell">
                                            <?= htmlspecialchars(substr($r['description'], 0, 60)) ?>
                                            <?= strlen($r['description']) > 60 ? '...' : '' ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = '';
                                        $statusIcon = '';
                                        $statusText = '';
                                        
                                        switch($r['status']) {
                                            case 'pending':
                                                $statusClass = 'status-pending';
                                                $statusIcon = 'fa-clock';
                                                $statusText = 'Na čekanju';
                                                break;
                                            case 'answered':
                                                $statusClass = 'status-answered';
                                                $statusIcon = 'fa-reply';
                                                $statusText = 'Odgovoreno';
                                                break;
                                            case 'scheduled':
                                                $statusClass = 'status-scheduled';
                                                $statusIcon = 'fa-calendar-check';
                                                $statusText = 'Zakazano';
                                                break;
                                            case 'completed':
                                                $statusClass = 'status-completed';
                                                $statusIcon = 'fa-check-circle';
                                                $statusText = 'Završeno';
                                                break;
                                            default:
                                                $statusClass = 'status-pending';
                                                $statusIcon = 'fa-question';
                                                $statusText = htmlspecialchars($r['status']);
                                        }
                                        ?>
                                        <span class="status-badge <?= $statusClass ?>">
                                            <i class="fas <?= $statusIcon ?> me-1"></i>
                                            <?= $statusText ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <?php if ($r['status'] === 'pending'): ?>
                                                <a href="/auto-servis/mechanic.php?controller=mechanic&action=showReplyForm&id=<?= $r['id'] ?>" 
                                                   class="btn-action btn-action-reply" 
                                                   title="Odgovori na zahtev">
                                                    <i class="fas fa-reply"></i>
                                                    <span>Odgovori</span>
                                                </a>
                                            <?php endif; ?>

                                            <?php if ($r['status'] !== 'completed'): ?>
                                                <a href="/auto-servis/mechanic.php?controller=mechanic&action=markCompleted&id=<?= $r['id'] ?>" 
                                                   class="btn-action btn-action-complete" 
                                                   title="Označi kao završeno"
                                                   onclick="return confirm('Da li ste sigurni da želite da označite ovaj zahtev kao završen?')">
                                                    <i class="fas fa-check"></i>
                                                    <span>Završi</span>
                                                </a>
                                            <?php endif; ?>

                                            <?php if ($r['status'] === 'completed'): ?>
                                                <span class="text-muted-custom">
                                                    <i class="fas fa-check-double me-1"></i>Završeno
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mechanic-footer">
        <div class="container text-center">
            <p class="mb-0">&copy; <?= date('Y') ?> Božidar AutoApp • Mehaničarski Panel</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/auto-servis/assets/js/mechanic.js"></script>
</body>
</html>