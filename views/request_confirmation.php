<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: ../../login.php");
    exit;
}
?>

<div class="container mt-4">
  <div class="alert alert-success">
    ✅ Zahtev je uspešno poslat mehaničaru!
  </div>
  <a href="/auto-servis/user.php?controller=request&action=showForm" class="btn btn-secondary">Pošalji još jedan</a>
  <a href="/auto-servis/views/dashboard.php" class="btn btn-outline-primary">Nazad na dashboard</a>
</div>
