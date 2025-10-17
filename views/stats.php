<?php
session_start();
require_once __DIR__ . '/../controllers/StatsController.php';
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Statistika vozila</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header class="bg-dark text-white p-3">
    <div class="container">
        <h1 class="h3">Statistika za vozilo</h1>
    </div>
</header>

<div class="container mt-4">
    <h4><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model'] . ' (' . $car['year'] . ') - ' . $car['registration']); ?></h4>

    <div class="card mt-3">
        <div class="card-body">
            <h5>Servisi</h5>
            <ul>
                <li>Ukupan broj servisa: <?php echo $serviceStats['service_count'] ?? 0; ?></li>
                <li>Ukupan trošak servisa: <?php echo $serviceStats['total_service_cost'] ?? 0; ?> RSD</li>
                <li>Najskuplji servis: <?php echo $serviceStats['max_service_cost'] ?? 0; ?> RSD</li>
            </ul>

            <h5 class="mt-4">Modifikacije</h5>
            <ul>
                <li>Ukupan broj modifikacija: <?php echo $modStats['mod_count'] ?? 0; ?></li>
                <li>Ukupan trošak modifikacija: <?php echo $modStats['total_mod_cost'] ?? 0; ?> RSD</li>
            </ul>
        </div>
    </div>

    <a href="dashboard.php" class="btn btn-secondary mt-3">Nazad na dashboard</a>
</div>

<footer class="bg-light text-center p-3 mt-5">
    &copy; <?php echo date('Y'); ?> Božidar AutoApp
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/main.js"></script>
</body>
</html>
