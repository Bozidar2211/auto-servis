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
  <title>Zahtev poslat</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
      color: white;
      font-family: 'Segoe UI', sans-serif;
    }
    .confirmation-box {
      background-color: rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      padding: 40px;
      text-align: center;
      animation: fadeIn 0.8s ease-in-out;
    }
    .confirmation-box h2 {
      font-size: 2rem;
      margin-bottom: 20px;
    }
    .confirmation-box i {
      font-size: 3rem;
      color: #4BB543;
      margin-bottom: 15px;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
  <div class="confirmation-box shadow-lg">
    <i class="bi bi-check-circle-fill"></i>
    <h2>Zahtev je uspešno poslat!</h2>
    <p class="mb-4">Vaš zahtev je prosleđen mehaničaru. Bićete obavešteni kada bude odgovoren.</p>
    <div class="d-grid gap-2 col-6 mx-auto">
      <a href="/auto-servis/user.php?controller=request&action=showForm" class="btn btn-light">📨 Pošalji još jedan</a>
      <a href="/auto-servis/views/dashboard.php" class="btn btn-outline-light">🏠 Nazad na dashboard</a>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
