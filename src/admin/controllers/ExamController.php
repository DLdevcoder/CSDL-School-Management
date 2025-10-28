<?php
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/base.php'; 
require_once __DIR__ . '/../services/ExamService.php';

class ExamController {
    protected ExamService $service;

    public function __construct() {
        $this->service = new ExamService();
    }

    public function list() {
        requireAdmin();
        if (isset($_GET['del'])) {
            $id = (int)$_GET['del'];
            $result = $this->service->deleteExam($id);

            if ($result === true) {
                $_SESSION['flash_message'] = "Đã xóa kỳ thi thành công!";
            } else {
                $_SESSION['flash_message'] = "Lỗi: " . $result;
            }
            header('Location: ' . BASE_URL . '/src/admin/index.php?page=exam&action=list');
            exit;
        }
        $exams = $this->service->getAllExams();
        ob_start();
        include __DIR__ . '/../presentation/exam/list.php';
        $content = ob_get_clean();
        include __DIR__ . '/../presentation/partials/layout.php';
    }

    public function create() {
        requireAdmin();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->service->createExam($_POST);
            if ($result === true) {
                $_SESSION['flash_message'] = "Thêm kỳ thi mới thành công!";
                header('Location: ' . BASE_URL . '/src/admin/index.php?page=exam&action=list');
                exit;
            } else {
                $error = $result;
            }
        }
        $formData = $this->service->getFormData();
        ob_start();
        include __DIR__ . '/../presentation/exam/create.php';
        $content = ob_get_clean();
        include __DIR__ . '/../presentation/partials/layout.php';
    }
}