<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validacija
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Molimo popunite sva polja.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Unesite validnu email adresu.';
    } elseif (strlen($password) < 6) {
        $error = 'Lozinka mora imati najmanje 6 karaktera.';
    } elseif ($password !== $confirm_password) {
        $error = 'Lozinke se ne poklapaju.';
    } else {
        $db = Database::getInstance();
        
        // Provera da li email već postoji
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $error = 'Email adresa je već registrovana.';
        } else {
            // Registracija novog korisnika
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
            
            if ($stmt->execute([$username, $email, $hashedPassword])) {
                $success = 'Registracija uspešna! Možete se prijaviti.';
                // Opciono: Automatska prijava
                // $_SESSION['user'] = [...];
                // header('Location: dashboard.php');
            } else {
                $error = 'Greška pri registraciji. Pokušajte ponovo.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registracija | Auto Servis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
    <!-- Animated Background -->
    <div class="animated-bg">
        <div class="carbon-fiber"></div>
        <div class="gradient-overlay"></div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-car-side"></i>
                Auto Servis
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php#features">Usluge</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php#locations">Lokacije</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php#contact">Kontakt</a>
                    </li>
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                        <a href="login.php" class="btn btn-login-nav">Prijava</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

<!-- Register Section -->
    <section class="auth-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <!-- Auth Card -->
                    <div class="auth-card fade-in">
                        <!-- Logo/Icon -->
                        <div class="auth-logo">
                            <div class="logo-icon">
                                <i class="fas fa-car-side"></i>
                            </div>
                            <h2>Dobrodošli</h2>
                            <p>Registrujte novi nalog</p>
                        </div>
<form method="POST" action="../controllers/AuthController.php" class="auth-form">
        <div class="form-group">
            <label for="username" class="form-label">Korisničko ime:</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="username" required>
        </div>
        <div class="form-group">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="vas.email@primer.com" required>
        </div>
        <div class="form-group">
            <label for="password" class="form-label">Lozinka:</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
        </div>
        <div class="form-group">
            <label for="confirm" class="form-label">Potvrdi lozinku:</label>
            <input type="password" name="confirm" id="confirm" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary">Registruj se</button>
    </form>

    <!-- Login Link -->
                        <div class="auth-footer">
                            <p>Nemate nalog?</p>
                            <a href="login.php" class="login-link">
                                Ulogujte se
                                <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
            </section>
    <!-- Footer -->
    <footer class="auth-footer-bottom">
        <div class="container text-center">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Božidar AutoApp • Sva prava zadržana</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/auth.js"></script>
</body>
</html>