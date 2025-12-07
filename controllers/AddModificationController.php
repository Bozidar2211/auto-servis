<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: ../views/login.php');
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/dashboard.php');
    exit;
}

require_once __DIR__ . '/../models/Modification.php';
require_once __DIR__ . '/../models/Car.php';

// Get form data - UPDATED to match your form field names
$carId = $_POST['car_id'] ?? null;
$modType = trim($_POST['mod_type'] ?? '');
$category = $_POST['category'] ?? '';
$description = trim($_POST['description'] ?? '');
$installationDate = $_POST['installation_date'] ?? '';
$installationCost = $_POST['installation_cost'] ?? null;
$partsCost = $_POST['parts_cost'] ?? null;
$status = $_POST['status'] ?? '';
$warranty = $_POST['warranty'] ?? null;
$notes = trim($_POST['notes'] ?? '');

// Initialize error array
$errors = [];

// Validation - Car ID
if (empty($carId) || !is_numeric($carId)) {
    $errors[] = 'Neispravan ID automobila.';
}

// Validation - Mod Type
if (empty($modType)) {
    $errors[] = 'Tip modifikacije je obavezan.';
} elseif (strlen($modType) < 3) {
    $errors[] = 'Tip modifikacije mora imati najmanje 3 karaktera.';
}

// Validation - Category
if (empty($category)) {
    $errors[] = 'Kategorija je obavezna.';
}

// Validation - Installation Date
if (empty($installationDate)) {
    $errors[] = 'Datum instalacije je obavezan.';
} else {
    // Check format
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $installationDate)) {
        $errors[] = 'Datum mora biti u formatu YYYY-MM-DD.';
    } else {
        // Validate date is real
        $dateObj = DateTime::createFromFormat('Y-m-d', $installationDate);
        if (!$dateObj || $dateObj->format('Y-m-d') !== $installationDate) {
            $errors[] = 'Unet datum nije validan.';
        } else {
            // Check if date is not in the future
            $today = new DateTime();
            $today->setTime(0, 0, 0);
            if ($dateObj > $today) {
                $errors[] = 'Datum instalacije ne može biti u budućnosti.';
            }
        }
    }
}

// Validation - Status
if (empty($status)) {
    $errors[] = 'Status je obavezan.';
} elseif (!in_array($status, ['Planirana', 'U toku', 'Završena'])) {
    $errors[] = 'Neispravan status.';
}

// Validation - Installation Cost (optional)
if ($installationCost !== null && $installationCost !== '') {
    if (!is_numeric($installationCost) || $installationCost < 0) {
        $errors[] = 'Cena instalacije mora biti pozitivan broj.';
    } else {
        $installationCost = floatval($installationCost);
    }
} else {
    $installationCost = 0;
}

// Validation - Parts Cost (optional)
if ($partsCost !== null && $partsCost !== '') {
    if (!is_numeric($partsCost) || $partsCost < 0) {
        $errors[] = 'Cena delova mora biti pozitivan broj.';
    } else {
        $partsCost = floatval($partsCost);
    }
} else {
    $partsCost = 0;
}

// Validation - Warranty (optional)
if ($warranty !== null && $warranty !== '') {
    if (!is_numeric($warranty) || $warranty < 0 || $warranty > 120) {
        $errors[] = 'Garantija mora biti između 0 i 120 meseci.';
    } else {
        $warranty = intval($warranty);
    }
} else {
    $warranty = null;
}

// Check if car belongs to user
if (empty($errors)) {
    $car = Car::getById($carId);
    if (!$car || $car['user_id'] != $_SESSION['user']['id']) {
        $errors[] = 'Nemate pristup ovom automobilu.';
    }
}

// If there are errors, redirect back with error messages
if (!empty($errors)) {
    $_SESSION['error_message'] = implode('<br>', $errors);
    header('Location: ../views/add_modification.php?car_id=' . $carId);
    exit;
}

// Calculate total cost
$totalCost = $installationCost + $partsCost;

// Prepare data for insertion
$data = [
    'car_id' => $carId,
    'mod_type' => $modType,
    'category' => $category,
    'description' => $description,
    'installation_date' => $installationDate,
    'installation_cost' => $installationCost,
    'parts_cost' => $partsCost,
    'total_cost' => $totalCost,
    'status' => $status,
    'warranty' => $warranty,
    'notes' => $notes
];

// Insert modification using your existing method
try {
    // Check if you have a create() method or add() method
    if (method_exists('Modification', 'create')) {
        $modificationId = Modification::create($data);
    } elseif (method_exists('Modification', 'add')) {
        // If your model uses add() method instead
        $modificationId = Modification::add(
            $carId, 
            $modType, 
            $category, 
            $description, 
            $installationDate, 
            $installationCost, 
            $partsCost, 
            $totalCost, 
            $status, 
            $warranty, 
            $notes
        );
    } else {
        throw new Exception('Modification model method not found');
    }
    
    if ($modificationId) {
        $_SESSION['success_message'] = 'Modifikacija je uspešno dodata!';
        header('Location: ../views/modifications.php?car_id=' . $carId);
        exit;
    } else {
        $_SESSION['error_message'] = 'Greška pri dodavanju modifikacije. Molimo pokušajte ponovo.';
        header('Location: ../views/add_modification.php?car_id=' . $carId);
        exit;
    }
} catch (Exception $e) {
    error_log('Error adding modification: ' . $e->getMessage());
    $_SESSION['error_message'] = 'Došlo je do greške. Molimo pokušajte ponovo.';
    header('Location: ../views/add_modification.php?car_id=' . $carId);
    exit;
}
?>