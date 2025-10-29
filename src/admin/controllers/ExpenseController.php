<?php
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/base.php'; 
require_once __DIR__ . '/../services/ExpenseService.php';

class ExpenseController {
    protected ExpenseService $service;

    public function __construct() {
        $this->service = new ExpenseService();
    }

    public function list() {
        requireAdmin();
        if (isset($_GET['del'])) {
            $id = (int)$_GET['del'];
            $result = $this->service->deleteExpense($id);

            if ($result === true) {
                $_SESSION['flash_message'] = "Đã xóa khoản chi phí thành công!";
            } else {
                $_SESSION['flash_message'] = "Lỗi: " . $result;
            }
            header('Location: ' . BASE_URL . '/src/admin/index.php?page=expense&action=list');
            exit;
        }
        $expenses = $this->service->getAllExpenses();
        ob_start();
        include __DIR__ . '/../presentation/expense/list.php';
        $content = ob_get_clean();
        include __DIR__ . '/../presentation/partials/layout.php';
    }

    public function create() {
        requireAdmin();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->service->createExpense($_POST);
            if ($result === true) {
                $_SESSION['flash_message'] = "Thêm chi phí mới thành công!";
                header('Location: ' . BASE_URL . '/src/admin/index.php?page=expenses&action=list');
                exit;
            } else {
                $error = $result; 
            }
        }

        ob_start();
        include __DIR__ . '/../presentation/expense/create.php';
        $content = ob_get_clean();
        include __DIR__ . '/../presentation/partials/layout.php';
    }
}