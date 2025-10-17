<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<header class="bg-dark text-white p-3">
    <div class="container">
        <h1 class="h3">👑 Admin Panel</h1>
        <p class="mb-0">Dobrodošli, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>!</p>
    </div>
</header>

<div class="container mt-4">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">📋 Upravljanje korisnicima</h5>
                    <p class="card-text">Pregledaj, izmeni ili obriši korisnike sistema.</p>
                    <a href="/auto-servis/admin.php?controller=user&action=index" class="btn btn-outline-primary">Pregled korisnika</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">📊 Sistem statistika</h5>
                    <p class="card-text">Pregledaj ukupne podatke o vozilima, servisima i korisnicima.</p>
                    <a href="/auto-servis/admin.php?controller=report&action=overview" class="btn btn-outline-info">Pregled statistike</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">💰 Troškovi po korisniku</h5>
                    <p class="card-text">Analiza ukupnih troškova servisa i modifikacija po korisniku.</p>
                    <a href="/auto-servis/admin.php?controller=report&action=costsByUser" class="btn btn-outline-success">Troškovi</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">🔧 Najčešći tipovi servisa</h5>
                    <p class="card-text">Statistika najčešće unetih servisa u sistemu.</p>
                    <a href="/auto-servis/admin.php?controller=report&action=topServiceTypes" class="btn btn-outline-warning">Tipovi servisa</a>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="/auto-servis/admin.php?controller=user&action=dashboard" class="btn btn-secondary">Nazad na admin panel</a>
        <a href="/auto-servis/views/logout.php" class="btn btn-outline-danger">Odjava</a>
    </div>
</div>

<footer class="bg-light text-center p-3 mt-5">
    &copy; <?php echo date('Y'); ?> Božidar AutoApp
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/main.js"></script>
</body>
</html>
