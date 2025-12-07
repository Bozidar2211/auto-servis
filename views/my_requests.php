<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: ../../login.php");
    exit;
}
$requests = $requests ?? [];
$filter = $_GET['filter'] ?? 'active';
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moji Zahtevi | Auto Servis</title>
<!-- Favicon -->
<link rel="icon" href="/auto-servis/assets/img/favicon.png" type="image/png">

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Custom CSS -->
<link rel="stylesheet" href="/auto-servis/assets/css/style.css">
<link rel="stylesheet" href="/auto-servis/assets/css/my_requests.css">
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
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/auto-servis/user.php?controller=request&action=showForm">
                        <i class="fas fa-plus-circle"></i>
                        Novi Zahtev
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
                <i class="fas fa-clipboard-list"></i>
            </div>
            <h1 class="header-title">Moji Zahtevi</h1>
            <p class="header-subtitle">Pregled i upravljanje servisnim zahtevima</p>
        </div>
    </div>
</section>
<!-- Main Content -->
<section class="content-section">
    <div class="container">
    <!-- Filter Buttons -->
    <div class="filter-section fade-in">
        <div class="filter-buttons">
            <a href="/auto-servis/user.php?controller=request&action=myRequests&filter=active" 
               class="btn-filter <?php echo $filter === 'active' ? 'active' : ''; ?>">
                <i class="fas fa-clock"></i>
                Aktivni
            </a>
            <a href="/auto-servis/user.php?controller=request&action=myRequests&filter=completed" 
               class="btn-filter <?php echo $filter === 'completed' ? 'active' : ''; ?>">
                <i class="fas fa-check-circle"></i>
                Završeni
            </a>
            <a href="/auto-servis/user.php?controller=request&action=myRequests&filter=all" 
               class="btn-filter <?php echo $filter === 'all' ? 'active' : ''; ?>">
                <i class="fas fa-list"></i>
                Svi
            </a>
        </div>
        <button class="btn-refresh" onclick="location.reload()">
            <i class="fas fa-sync-alt"></i>
        </button>
    </div>

    <!-- Requests List -->
    <?php if (empty($requests)): ?>
        <div class="empty-state fade-in">
            <i class="fas fa-inbox"></i>
            <h3>Nema zahteva</h3>
            <p>Trenutno nemate <?php echo $filter === 'active' ? 'aktivnih' : ''; ?> zahteva</p>
            <a href="/auto-servis/user.php?controller=request&action=showForm" class="btn-primary-custom">
                <i class="fas fa-plus me-2"></i>
                Kreiraj novi zahtev
            </a>
        </div>
    <?php else: ?>
        <div class="requests-grid">
            <?php foreach ($requests as $index => $request): ?>
                <div class="request-card fade-in" style="animation-delay: <?php echo $index * 0.1; ?>s;">
                    
                    <!-- Card Header -->
                    <div class="request-header">
                        <div class="request-info">
                            <h3 class="request-title">
                                <i class="fas fa-car"></i>
                                <?php echo htmlspecialchars($request['car_model']); ?>
                            </h3>
                            <p class="request-meta">
                                <i class="fas fa-calendar"></i>
                                <?php echo date('d.m.Y H:i', strtotime($request['created_at'])); ?>
                            </p>
                        </div>
                        
                        <?php
                        $statusClass = '';
                        $statusIcon = '';
                        $statusText = '';
                        
                        switch($request['status']) {
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
                                $statusText = htmlspecialchars($request['status']);
                        }
                        ?>
                        
                        <span class="status-badge <?php echo $statusClass; ?>">
                            <i class="fas <?php echo $statusIcon; ?>"></i>
                            <?php echo $statusText; ?>
                        </span>
                    </div>

                    <!-- Card Body -->
                    <div class="request-body">
                        <div class="request-detail">
                            <span class="detail-label">
                                <i class="fas fa-user-tie"></i>
                                Mehaničar
                            </span>
                            <span class="detail-value">
                                <?php echo htmlspecialchars($request['mechanic_name']); ?>
                            </span>
                        </div>

                        <div class="request-detail">
                            <span class="detail-label">
                                <i class="fas fa-comment"></i>
                                Opis problema
                            </span>
                            <span class="detail-value">
                                <?php echo htmlspecialchars($request['description']); ?>
                            </span>
                        </div>

                        <?php if ($request['status'] === 'answered' || $request['status'] === 'scheduled' || $request['status'] === 'completed'): ?>
                            <div class="mechanic-response">
                                <div class="response-header">
                                    <i class="fas fa-reply"></i>
                                    <h4>Odgovor mehaničara</h4>
                                </div>

                                <?php if (!empty($request['proposed_price'])): ?>
                                    <div class="response-detail">
                                        <span class="response-label">Predložena cena:</span>
                                        <span class="response-value price">
                                            <?php echo number_format($request['proposed_price'], 2); ?> RSD
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($request['proposed_date'])): ?>
                                    <div class="response-detail">
                                        <span class="response-label">Predloženi datum:</span>
                                        <span class="response-value">
                                            <i class="fas fa-calendar"></i>
                                            <?php echo date('d.m.Y', strtotime($request['proposed_date'])); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($request['note'])): ?>
                                    <div class="response-detail">
                                        <span class="response-label">Napomena:</span>
                                        <span class="response-value">
                                            <?php echo nl2br(htmlspecialchars($request['note'])); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Card Footer -->
                    <div class="request-footer">
                        <?php if ($request['status'] === 'pending'): ?>
                            <button class="btn-action btn-waiting" disabled>
                                <i class="fas fa-hourglass-half"></i>
                                Čeka se odgovor
                            </button>
                        <?php endif; ?>

                        <?php if ($request['status'] === 'answered'): ?>
                            <a href="/auto-servis/user.php?controller=request&action=schedule&id=<?php echo $request['id']; ?>" 
                                class="btn-action btn-accept">
                                    <i class="fas fa-check"></i>
                                Prihvati i zakaži
                            </a>

                            <a href="/auto-servis/user.php?controller=request&action=decline&id=<?php echo $request['id']; ?>" 
                                class="btn-action btn-decline">
                                    <i class="fas fa-times"></i>
                                Odbij
                            </a>

                        <?php endif; ?>

                        <?php if ($request['status'] === 'scheduled'): ?>
                            <button class="btn-action btn-scheduled" disabled>
                                <i class="fas fa-calendar-check"></i>
                                Zakazano
                            </button>
                        <?php endif; ?>

                        <?php if ($request['status'] === 'completed'): ?>
                            <a href="/auto-servis/user.php?controller=service&action=createFromRequest&id=<?php echo $request['id']; ?>" 
                               class="btn-action btn-add-service">
                                <i class="fas fa-plus-circle"></i>
                                Dodaj u servisnu istoriju
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
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
<script src="/auto-servis/assets/js/my_requests.js"></script>
</body>
</html>