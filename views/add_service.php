<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$carId = $_GET['car_id'] ?? null;
$prefill = $_SESSION['prefill_request'] ?? null;

if (!$carId) {
    echo "ID vozila nije prosleđen.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Dodaj servis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="bg-dark text-white p-3">
        <div class="container">
            <h1 class="h3">Dodavanje servisa</h1>
        </div>
    </header>

    <div class="container mt-4">
        <?php if ($prefill): ?>
            <div class="alert alert-info">
                Servis se dodaje na osnovu zahteva za vozilo: <strong><?= htmlspecialchars($prefill['car_model']) ?></strong>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="../controllers/AddServiceController.php">
                    <input type="hidden" name="car_id" value="<?php echo $carId; ?>">

                    <div class="mb-3">
                        <label for="service_date" class="form-label">Datum servisa:</label>
                        <input type="date" name="service_date" id="service_date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Opis:</label>
                        <textarea name="description" id="description" class="form-control" required><?php echo $prefill['description'] ?? ''; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="cost" class="form-label">Trošak (RSD):</label>
                        <input type="number" name="cost" id="cost" step="0.01" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="mileage" class="form-label">Kilometraža:</label>
                        <input type="number" name="mileage" id="mileage" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Sačuvaj</button>
                    <a href="services.php?car_id=<?php echo $carId; ?>" class="btn btn-secondary ms-2">Nazad</a>
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

<?php unset($_SESSION['prefill_request']); ?>
