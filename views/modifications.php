<?php
session_start();
require_once __DIR__ . '/../controllers/ModificationController.php';
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Modifikacije vozila</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header class="bg-dark text-white p-3">
    <div class="container">
        <h1 class="h3">Modifikacije vozila</h1>
    </div>
</header>

<div class="container mt-4">
    <?php if (empty($modifications)): ?>
        <p>Nema evidentiranih modifikacija za ovo vozilo.</p>
    <?php else: ?>
        <ul class="list-group">
            <?php foreach ($modifications as $mod): ?>
                <li class="list-group-item">
                    <?php echo htmlspecialchars($mod['mod_date'] . ' — ' . $mod['description'] . ' — ' . $mod['cost'] . ' RSD'); ?>
                    <a href="edit_modification.php?id=<?php echo $mod['id']; ?>" class="btn btn-sm btn-outline-primary ms-2">Izmeni</a>
                    <form method="POST" action="../controllers/DeleteController.php" class="d-inline" data-confirm="Da li ste sigurni da želite da obrišete ovu modifikaciju?">
                        <input type="hidden" name="type" value="modification">
                        <input type="hidden" name="id" value="<?php echo $mod['id']; ?>">
                        <input type="hidden" name="car_id" value="<?php echo $carId; ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger ms-2">Obriši</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <div class="mt-3">
        <a href="add_modification.php?car_id=<?php echo $carId; ?>" class="btn btn-success">Dodaj modifikaciju</a>
        <a href="dashboard.php" class="btn btn-secondary ms-2">Nazad na dashboard</a>
    </div>
</div>

<footer class="bg-light text-center p-3 mt-5">
    &copy; <?php echo date('Y'); ?> Božidar AutoApp
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/main.js"></script>
</body>
</html>
