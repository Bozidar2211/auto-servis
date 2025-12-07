<?php
require_once __DIR__ . '/../models/MechanicModel.php';

class MechanicController {
    private $model;

    public function __construct($db) {
        $this->model = new MechanicModel($db);
    }

    // Prikaz mehaničarskog panela sa filterom
    public function dashboard($mechanic_id, $filter = 'active') {
        $requests = $this->model->getRequests($mechanic_id, $filter);

        // Statistika
        $total = count($requests);
        $pending = count(array_filter($requests, fn($r) => $r['status'] === 'pending'));
        $answered = count(array_filter($requests, fn($r) => $r['status'] === 'answered'));
        $scheduled = count(array_filter($requests, fn($r) => $r['status'] === 'scheduled'));
        $completed = count(array_filter($requests, fn($r) => $r['status'] === 'completed'));

        include __DIR__ . '/../views/mechanic/mechanic_dashboard.php';
    }

    // Prikaz forme za odgovor
    public function showReplyForm($request_id) {
        include __DIR__ . '/../views/mechanic/reply_form.php';
    }

    // Obrada odgovora na zahtev
    public function reply($request_id, $price, $date, $note) {
    return $this->model->replyToRequest($request_id, $price, $date, $note);
}

    // Označavanje zahteva kao završenog
    public function markCompleted($request_id) {
        $this->model->markRequestCompleted($request_id);

        header("Location: mechanic.php?controller=mechanic&action=dashboard&completed=1");
        exit;
    }
}
