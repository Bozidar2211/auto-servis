<?php
require_once __DIR__ . '/../models/RequestModel.php';

class RequestController {
    private $model;

    public function __construct($db) {
        $this->model = new RequestModel($db);
    }

    public function showForm($user_id) {
        $cars = $this->model->getUserCars($user_id);
        $mechanics = $this->model->getAvailableMechanics();
        include __DIR__ . '/../views/request_form.php';
    }

    public function submit($user_id, $mechanic_id, $car_id, $description) {
        $this->model->create($user_id, $mechanic_id, $car_id, $description);
        header("Location: /auto-servis/user.php?controller=request&action=confirmation");
        exit;
    }

    public function confirmation() {
        include __DIR__ . '/../views/request_confirmation.php';
    }

    public function myRequests($user_id) {
    $requests = $this->model->getUserRequestsWithReplies($user_id);
    include __DIR__ . '/../views/my_requests.php';
}

}
