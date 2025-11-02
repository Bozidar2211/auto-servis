<?php session_start(); 
require_once __DIR__ . '/../utils/SeedData.php';
SeedData::ensureAdminExists();
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Prijava</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header>
  <div class="container text-center py-4">
    <h1 class="display-6 text-warning"><i class="bi bi-box-arrow-in-right me-2"></i>Prijava</h1>
    <p class="text-muted">Pristupite svom nalogu</p>
  </div>
</header>

<div class="container">
  <div class="login-wrapper">
    <h2 class="text-center"><i class="bi bi-person-fill-lock me-2"></i>Prijava korisnika</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="../controllers/AuthController.php">
        <div class="mb-3">
            <label for="email" class="form-label">Email adresa</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="vaš.email@example.com" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Lozinka</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">🔐 Prijavi se</button>
    </form>

    <div class="login-footer">
      <p class="mt-4">Nemate nalog? <a href="register.php">Registrujte se</a></p>
    </div>
  </div>
</div>

<footer class="text-center mt-5 py-3">
    &copy; <?php echo date('Y'); ?> Božidar AutoApp
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/main.js"></script>
</body>
</html>
