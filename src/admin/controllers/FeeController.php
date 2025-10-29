<?php
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/base.php'; 
require_once __DIR__ . '/../services/FeeService.php';

class FeeController {
    protected FeeService $service;

    public function __construct() {
        $this->service = new FeeService();
    }

    public function list() {
        requireAdmin();
        if (isset($_GET['del'])) {
            $id = (int)$_GET['del'];
            $result = $this->service->deleteFeeRecord($id);

            if ($result === true) {
                $_SESSION['flash_message'] = "Đã xóa bản ghi học phí thành công!";
            } else {
                $_SESSION['flash_message'] = "Lỗi: " . $result;
            }
            header('Location: ' . BASE_URL . '/src/admin/index.php?page=fee&action=list');
            exit;
        }
        $feeRecords = $this->service->getAllFeeRecords();
        ob_start();
        include __DIR__ . '/../presentation/fee/list.php';
        $content = ob_get_clean();
        include __DIR__ . '/../presentation/partials/layout.php';
    }
}