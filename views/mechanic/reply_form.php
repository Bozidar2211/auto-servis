<?php

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'mechanic') {
    header("Location: ../../login.php");
    exit;
}

$request_id = $_GET['id'] ?? null;
?>

<!DOCTYPE html>
<html lang="sr">
<head>
  <meta charset="UTF-8">
  <title>Odgovor na zahtev</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<header class="bg-dark text-white p-3">
  <div class="container d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">🔧 Odgovor na servisni zahtev</h1>
    <div>Ulogovani ste kao: <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong></div>
  </div>
</header>

<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST" action="/auto-servis/mechanic.php?controller=mechanic&action=reply">
        <input type="hidden" name="request_id" value="<?= htmlspecialchars($request_id) ?>">

        <div class="mb-3">
          <label for="price" class="form-label">Predložena cena</label>
          <input type="number" name="price" id="price" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="date" class="form-label">Predloženi datum</label>
          <input type="date" name="date" id="date" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="note" class="form-label">Napomena</label>
          <textarea name="note" id="note" class="form-control" rows="4"></textarea>
        </div>

        <div class="d-flex justify-content-between">
          <a href="/auto-servis/mechanic.php?controller=mechanic&action=dashboard" class="btn btn-secondary">Nazad</a>
          <button type="submit" class="btn btn-primary">Pošalji odgovor</button>
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
