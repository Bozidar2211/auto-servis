<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../dashboard.php');
    exit;
}

$user = $user ?? null;
$roles = $roles ?? [];
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Izmena korisnika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <?php if (!$user || !is_array($user)): ?>
        <div class="alert alert-danger">Korisnik nije pronađen ili podaci nisu dostupni.</div>
        <a href="/auto-servis/admin.php?controller=user&action=index" class="btn btn-secondary mt-3">Nazad</a>
    <?php else: ?>
        <h2 class="mb-4">✏️ Izmena korisnika</h2>
        <form action="/auto-servis/admin.php?controller=user&action=update" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">

            <div class="mb-3">
                <label for="username" class="form-label">Korisničko ime:</label>
                <input type="text" class="form-control" id="username" name="username"
                       value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" class="form-control" id="email" name="email"
                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Uloga:</label>
                <select name="role" id="role" class="form-select" required>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?php echo $role; ?>" <?php echo ($user['role'] === $role) ? 'selected' : ''; ?>>
                            <?php echo ucfirst($role); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Sačuvaj izmene</button>
            <a href="/auto-servis/admin.php?controller=user&action=dashboard" class="btn btn-secondary">Nazad na admin panel</a>
        </form>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
