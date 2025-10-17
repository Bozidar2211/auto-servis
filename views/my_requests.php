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
  <title>Moji zahtevi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<header class="bg-dark text-white p-3">
  <div class="container d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">📋 Moji servisni zahtevi</h1>
    <div>Ulogovani ste kao: <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong></div>
  </div>
</header>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Zahtevi</h4>
    <div>
      <a href="/auto-servis/user.php?controller=request&action=myRequests&filter=active" class="btn btn-outline-primary btn-sm me-2">Aktivni</a>
      <a href="/auto-servis/user.php?controller=request&action=myRequests&filter=all" class="btn btn-outline-secondary btn-sm">Svi</a>
    </div>
  </div>
<div class="mb-3">
  <a href="/auto-servis/views/dashboard.php" class="btn btn-outline-dark btn-sm">⬅ Početna</a>
</div>

  <?php if (empty($requests)): ?>
    <div class="alert alert-info">Nemate nijedan zahtev.</div>
  <?php else: ?>
    <table class="table table-bordered table-hover">
      <thead class="table-dark">
        <tr>
          <th>Vozilo</th>
          <th>Mehaničar</th>
          <th>Opis</th>
          <th>Status</th>
          <th>Cena</th>
          <th>Datum</th>
          <th>Napomena</th>
          <th>Akcija</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($requests as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['car_model']) ?></td>
            <td><?= htmlspecialchars($r['mechanic_name']) ?></td>
            <td><?= htmlspecialchars($r['description']) ?></td>
            <td><?= htmlspecialchars($r['status']) ?></td>
            <td><?= $r['proposed_price'] ? htmlspecialchars($r['proposed_price']) . ' RSD' : '-' ?></td>
            <td><?= $r['proposed_date'] ?? '-' ?></td>
            <td><?= $r['note'] ?? '-' ?></td>
            <td class="d-flex gap-2">
              <?php if ($r['status'] === 'answered'): ?>
                <a href="/auto-servis/user.php?controller=request&action=schedule&id=<?= $r['id'] ?>" class="btn btn-sm btn-warning">Zakazi</a>
              <?php endif; ?>

              <?php if ($r['status'] === 'completed'): ?>
                <a href="/auto-servis/user.php?controller=service&action=createFromRequest&id=<?= $r['id'] ?>" class="btn btn-sm btn-success">Dodaj servis</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<footer class="bg-light text-center p-3 mt-5">
  &copy; <?= date('Y') ?> Božidar AutoApp
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
