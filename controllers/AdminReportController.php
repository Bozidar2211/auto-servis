<?php
require_once __DIR__ . '/../models/Report.php';

class AdminReportController {
    public function overview() {
        $stats = Report::getSystemStats();
        include __DIR__ . '/../views/admin/reports.php';
    }

    public function costsByUser() {
        $costs = Report::getCostsByUser();
        include __DIR__ . '/../views/admin/costs_by_user.php';
    }

    public function topServiceTypes() {
        $types = Report::getTopServiceTypes();
        include __DIR__ . '/../views/admin/top_service_types.php';
    }
}
