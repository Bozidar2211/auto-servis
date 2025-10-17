<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: ../../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
  <meta charset="UTF-8">
  <title>Novi servisni zahtev</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<header class="bg-dark text-white p-3">
  <div class="container d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">📨 Novi servisni zahtev</h1>
    <div>Ulogovani ste kao: <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong></div>
  </div>
</header>

<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST" action="/auto-servis/user.php?controller=request&action=submit">
        <div class="mb-3">
          <label for="car_id" class="form-label">Vozilo</label>
          <select name="car_id" id="car_id" class="form-select" required>
            <?php foreach ($cars as $car): ?>
              <option value="<?= $car['id'] ?>"><?= htmlspecialchars($car['model']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="mechanic_id" class="form-label">Mehaničar</label>
          <select name="mechanic_id" id="mechanic_id" class="form-select" required>
            <?php foreach ($mechanics as $m): ?>
              <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['username']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="description" class="form-label">Opis problema</label>
          <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
        </div>

        <div class="d-flex justify-content-between">
          <a href="/auto-servis/views/dashboard.php" class="btn btn-secondary">Nazad</a>
          <button type="submit" class="btn btn-primary">Pošalji zahtev</button>
        </div>
      </form>
    </div>
  </div>
</div>

<footer class="bg-light text-center p-3 mt-5">
  &copy; <?= date('Y') ?> Božidar AutoApp
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
