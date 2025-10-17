<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Dodaj automobil</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="bg-dark text-white p-3">
        <div class="container">
            <h1 class="h3">Dodavanje automobila</h1>
        </div>
    </header>

    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="../controllers/AddCarController.php">
                    <div class="mb-3">
                        <label for="brand" class="form-label">Marka:</label>
                        <input type="text" name="brand" id="brand" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="model" class="form-label">Model:</label>
                        <input type="text" name="model" id="model" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="year" class="form-label">Godina:</label>
                        <input type="number" name="year" id="year" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="registration" class="form-label">Registracija:</label>
                        <input type="text" name="registration" id="registration" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Dodaj</button>
                    <a href="dashboard.php" class="btn btn-secondary ms-2">Nazad</a>
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
