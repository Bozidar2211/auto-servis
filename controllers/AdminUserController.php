<?php
require_once __DIR__ . '/../models/User.php';

class AdminUserController {
    public function index() {
        $users = User::getAll() ?? [];
        include __DIR__ . '/../views/admin/users.php';
    }

    public function dashboard() {
        include __DIR__ . '/../views/admin/admin_dashboard.php';
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            header('Location: /auto-servis/admin.php?controller=user&action=index');
            exit;
        }

        $user = User::getById($id);
        if (!$user || !is_array($user)) {
            header('Location: /auto-servis/admin.php?controller=user&action=index');
            exit;
        }

        // ✅ koristi helper iz modela
        $roles = User::availableRoles();
        include __DIR__ . '/../views/admin/edit_user.php';
    }

    public function update() {
        $data = $_POST;
        if (
            !empty($data['id']) && is_numeric($data['id']) &&
            !empty($data['username']) &&
            filter_var($data['email'], FILTER_VALIDATE_EMAIL) &&
            in_array($data['role'], User::availableRoles()) // ✅ koristi sve dozvoljene role
        ) {
            User::update($data);
        }
        header('Location: /auto-servis/admin.php?controller=user&action=index');
        exit;
    }

    public function delete() {
        $id = $_POST['id'] ?? null;

        if ($id && is_numeric($id)) {
            $success = User::delete($id);

            if (!$success) {
                $_SESSION['delete_error'] = "Korisnik ne može biti obrisan jer ima povezane podatke.";
            }
        }

        header('Location: /auto-servis/admin.php?controller=user&action=index');
        exit;
    }
}
