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
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<footer class="bg-light text-center p-3 mt-5">
  &copy; <?= date('Y') ?> Božidar AutoApp
</footer>

</body>
</html>
