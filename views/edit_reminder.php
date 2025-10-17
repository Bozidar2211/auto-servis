<?php
session_start();
require_once __DIR__ . '/../models/Reminder.php';

$reminder = Reminder::getById($_GET['id']);
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Izmeni podsetnik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header class="bg-dark text-white p-3">
    <div class="container">
        <h1 class="h3">Izmena podsetnika</h1>
    </div>
</header>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="../controllers/EditController.php">
                <input type="hidden" name="type" value="reminder">
                <input type="hidden" name="id" value="<?php echo $reminder['id']; ?>">
                <input type="hidden" name="car_id" value="<?php echo $reminder['car_id']; ?>">

                <div class="mb-3">
                    <label for="reminder_date" class="form-label">Datum:</label>
                    <input type="date" name="reminder_date" id="reminder_date" class="form-control" value="<?php echo $reminder['reminder_date']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="note" class="form-label">Napomena:</label>
                    <textarea name="note" id="note" class="form-control" required><?php echo $reminder['note']; ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Sačuvaj izmene</button>
                <a href="reminders.php?car_id=<?php echo $reminder['car_id']; ?>" class="btn btn-secondary ms-2">Nazad</a>
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
