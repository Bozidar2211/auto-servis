<?php
session_start();
require_once __DIR__ . '/../models/Modification.php';

$mod = Modification::getById($_GET['id']);
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Izmeni modifikaciju</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header class="bg-dark text-white p-3">
    <div class="container">
        <h1 class="h3">Izmena modifikacije</h1>
    </div>
</header>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="../controllers/EditController.php">
                <input type="hidden" name="type" value="modification">
                <input type="hidden" name="id" value="<?php echo $mod['id']; ?>">
                <input type="hidden" name="car_id" value="<?php echo $mod['car_id']; ?>">

                <div class="mb-3">
                    <label for="mod_date" class="form-label">Datum:</label>
                    <input type="date" name="mod_date" id="mod_date" class="form-control" value="<?php echo $mod['mod_date']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Opis:</label>
                    <textarea name="description" id="description" class="form-control" required><?php echo $mod['description']; ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="cost" class="form-label">Trošak:</label>
                    <input type="number" name="cost" id="cost" step="0.01" class="form-control" value="<?php echo $mod['cost']; ?>" required>
                </div>

                <button type="submit" class="btn btn-primary">Sačuvaj izmene</button>
                <a href="modifications.php?car_id=<?php echo $mod['car_id']; ?>" class="btn btn-secondary ms-2">Nazad</a>
            </form>
        </div>
    </div>
</div>

<footer class="bg-light text-center p-3 mt-5">
    &copy; <?php echo date('Y'); ?> Božidar AutoApp
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/main.js"></script>
</body>
</html>
