<?php
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/base.php'; 
require_once __DIR__ . '/../services/RegistrationService.php';

class RegistrationController {
    protected RegistrationService $service;

    public function __construct() {
        $this->service = new RegistrationService();
    }

    public function list() {
        requireAdmin();
        if (isset($_GET['del'])) {
            $id = (int)$_GET['del'];
            $result = $this->service->deleteRegistration($id);

            if ($result === true) {
                $_SESSION['flash_message'] = "Đã xóa bản ghi đăng ký thành công!";
            } else {
                $_SESSION['flash_message'] = "Lỗi: " . $result;
            }
            header('Location: ' . BASE_URL . '/src/admin/index.php?page=registration&action=list');
            exit;
        }
        $registrations = $this->service->getAllRegistrations();
        ob_start();
        include __DIR__ . '/../presentation/registration/list.php';
        $content = ob_get_clean();
        include __DIR__ . '/../presentation/partials/layout.php';
    }
}