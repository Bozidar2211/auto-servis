<?php
session_start();
$carId = $_GET['car_id'] ?? null;
if (!$carId) {
    echo "ID vozila nije prosleđen.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Dodaj modifikaciju</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="bg-dark text-white p-3">
        <div class="container">
            <h1 class="h3">Dodavanje modifikacije</h1>
        </div>
    </header>

    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="../controllers/AddModificationController.php">
                    <input type="hidden" name="car_id" value="<?php echo $carId; ?>">

                    <div class="mb-3">
                        <label for="mod_date" class="form-label">Datum modifikacije:</label>
                        <input type="date" name="mod_date" id="mod_date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Opis:</label>
                        <textarea name="description" id="description" class="form-control" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="cost" class="form-label">Trošak (RSD):</label>
                        <input type="number" name="cost" id="cost" step="0.01" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Sačuvaj</button>
                    <a href="modifications.php?car_id=<?php echo $carId; ?>" class="btn btn-secondary ms-2">Nazad</a>
                </form>
            </div>
        </div>
    </div>

    <footer class="bg-light text-center p-3 mt-5">
        &copy; <?php echo date('Y'); ?> Božidar AutoApp
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="../assets/js/main.js"></script>
</body>
</html>
