<?php
require_once __DIR__ . '/../models/MechanicModel.php';

class MechanicController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new MechanicModel($db);
    }

    public function dashboard($mechanic_id) {
        $requests = $this->model->getRequests($mechanic_id);

        // Statistika
        $total = count($requests);
        $pending = count(array_filter($requests, fn($r) => $r['status'] === 'pending'));
        $answered = count(array_filter($requests, fn($r) => $r['status'] === 'answered'));
        $scheduled = count(array_filter($requests, fn($r) => $r['status'] === 'scheduled'));
        $completed = count(array_filter($requests, fn($r) => $r['status'] === 'completed'));

        // Prosleđivanje podataka view-u
        include __DIR__ . '/../views/mechanic/mechanic_dashboard.php';
    }

    public function showReplyForm($request_id) {
        include __DIR__ . '/../views/mechanic/reply_form.php';
    }

    public function reply($request_id, $price, $date, $note) {
        $stmt = $this->db->prepare("
            UPDATE requests
            SET proposed_price = ?, proposed_date = ?, note = ?, status = 'answered'
            WHERE id = ?
        ");
        $stmt->execute([$price, $date, $note, $request_id]);

        header("Location: mechanic.php?controller=mechanic&action=dashboard&reply=success");
        exit;
    }
}
