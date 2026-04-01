<?php
require_once __DIR__ . '/../config/db.php';
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


    public function myRequests($user_id, $filter = 'active') {
        $requests = $this->model->getUserRequestsWithReplies($user_id, $filter);
        include __DIR__ . '/../views/my_requests.php';
    }

    public function schedule($request_id) {
        $this->model->scheduleRequest($request_id);
        header("Location: /auto-servis/user.php?controller=request&action=myRequests&scheduled=1");
        exit;
    }

    public function decline($requestId, $userId) {
        $request = $this->model->getRequestById($requestId);
        if ($request && $request['user_id'] == $userId) {
            $this->model->updateStatus($requestId, 'declined');
                header("Location: user.php?controller=request&action=myRequests");
            exit;
            
        } else {
        echo "<div class='container mt-5'><div class='alert alert-danger'>Zahtev nije pronađen ili ne pripada ovom korisniku.</div></div>";
    }
}

}

