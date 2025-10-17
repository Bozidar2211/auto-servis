<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../dashboard.php');
    exit;
}

$stats = $stats ?? [];
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Izveštaji</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<header class="bg-dark text-white p-3">
    <div class="container">
        <h1 class="h3">📊 Sistem Statistika</h1>
    </div>
</header>

<div class="container mt-4">
    <ul class="list-group">
        <li class="list-group-item">Ukupan broj korisnika: <strong><?php echo $stats['user_count'] ?? 0; ?></strong></li>
        <li class="list-group-item">Ukupan broj automobila: <strong><?php echo $stats['car_count'] ?? 0; ?></strong></li>
        <li class="list-group-item">Ukupan broj servisa: <strong><?php echo $stats['service_count'] ?? 0; ?></strong></li>
        <li class="list-group-item">Ukupan broj modifikacija: <strong><?php echo $stats['mod_count'] ?? 0; ?></strong></li>
        <li class="list-group-item">Ukupan broj podsetnika: <strong><?php echo $stats['reminder_count'] ?? 0; ?></strong></li>
    </ul>

    <div class="mt-4">
        <a href="/auto-servis/admin.php?controller=report&action=costsByUser" class="btn btn-outline-primary">Troškovi po korisniku</a>
        <a href="/auto-servis/admin.php?controller=report&action=topServiceTypes" class="btn btn-outline-info ms-2">Najčešći tipovi servisa</a>
        <a href="/auto-servis/admin.php?controller=user&action=dashboard" class="btn btn-secondary">Nazad na admin panel</a>
    </div>
</div>

<footer class="bg-light text-center p-3 mt-5">
    &copy; <?php echo date('Y'); ?> Božidar AutoApp
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/main.js"></script>
</body>
</html>
