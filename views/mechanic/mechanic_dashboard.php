<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'mechanic') {
    header("Location: ../../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
  <meta charset="UTF-8">
  <title>Mehaničarski panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<header class="bg-dark text-white p-3">
  <div class="container d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">🔧 Mehaničarski panel</h1>
    <div>Ulogovani ste kao: <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong></div>
  </div>
</header>

<div class="container mt-4">
  <h3 class="mb-4">📊 Statistika zahteva</h3>
  <div class="row g-3 mb-5">
    <div class="col-md-2">
      <div class="card text-center p-3">
        <h6>Ukupno</h6>
        <div class="fs-4"><?= $total ?></div>
      </div>
    </div>
    <div class="col-md-2">
      <div class="card text-center p-3">
        <h6>Na čekanju</h6>
        <div class="fs-4 text-warning"><?= $pending ?></div>
      </div>
    </div>
    <div class="col-md-2">
      <div class="card text-center p-3">
        <h6>Odgovoreno</h6>
        <div class="fs-4 text-primary"><?= $answered ?></div>
      </div>
    </div>
    <div class="col-md-2">
      <div class="card text-center p-3">
        <h6>Zakazano</h6>
        <div class="fs-4 text-info"><?= $scheduled ?></div>
      </div>
    </div>
    <div class="col-md-2">
      <div class="card text-center p-3">
        <h6>Završeno</h6>
        <div class="fs-4 text-success"><?= $completed ?></div>
      </div>
    </div>
  </div>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">📋 Pristigli zahtevi</h4>
    <div>
      <a href="/auto-servis/mechanic.php?controller=mechanic&action=dashboard&filter=active" class="btn btn-outline-primary btn-sm me-2">Aktivni</a>
      <a href="/auto-servis/mechanic.php?controller=mechanic&action=dashboard&filter=all" class="btn btn-outline-secondary btn-sm">Svi</a>
    </div>
  </div>

  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>Korisnik</th>
        <th>Vozilo</th>
        <th>Opis</th>
        <th>Status</th>
        <th>Akcija</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($requests as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['user_name']) ?></td>
          <td><?= htmlspecialchars($r['car_model']) ?></td>
          <td><?= htmlspecialchars($r['description']) ?></td>
          <td><?= htmlspecialchars($r['status']) ?></td>
          <td class="d-flex gap-2">
            <?php if ($r['status'] === 'pending'): ?>
              <a href="/auto-servis/mechanic.php?controller=mechanic&action=showReplyForm&id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary">Odgovori</a>
            <?php endif; ?>

            <?php if ($r['status'] !== 'completed'): ?>
              <a href="/auto-servis/mechanic.php?controller=mechanic&action=markCompleted&id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-success">Završi</a>
            <?php endif; ?>

            <?php if ($r['status'] === 'completed'): ?>
              <span class="text-muted">Završeno</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="mt-4 d-flex justify-content-between">
    <a href="/auto-servis/views/logout.php" class="btn btn-outline-danger">Odjava</a>
    <a href="/auto-servis/mechanic.php?controller=mechanic&action=dashboard&filter=<?= htmlspecialchars($_GET['filter'] ?? 'active') ?>" class="btn btn-secondary">Osveži panel</a>
  </div>
</div>

<footer class="bg-light text-center p-3 mt-5">
  &copy; <?= date('Y') ?> Božidar AutoApp
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
