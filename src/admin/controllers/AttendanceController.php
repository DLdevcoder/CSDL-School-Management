<?php
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/base.php';
require_once __DIR__ . '/../services/AttendanceService.php';

class AttendanceController {
    protected AttendanceService $service;

    public function __construct() {
        $this->service = new AttendanceService();
    }

    public function take() {
        requireAdmin();
        $courseId = (int)($_GET['id'] ?? 0);

        if ($courseId <= 0) {
            $_SESSION['flash_message'] = "Lỗi: ID khóa học không hợp lệ.";
            header('Location: ' . BASE_URL . '/src/admin/index.php?page=course&action=list');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->service->recordBulkAttendance($_POST);
            if ($result === true) {
                $_SESSION['flash_message'] = "Điểm danh thành công!";
            } else {
                $_SESSION['flash_message'] = "Lỗi: " . $result;
            }
            header('Location: ' . BASE_URL . '/src/admin/index.php?page=attendance&action=take&id=' . $courseId);
            exit;
        }

        $students = $this->service->getStudentsForAttendance($courseId);

        ob_start();
        include __DIR__ . '/../presentation/attendance/take.php';
        $content = ob_get_clean();
        include __DIR__ . '/../presentation/partials/layout.php';
    }

    public function history() {
        requireAdmin();
        $id = (int)($_GET['id'] ?? 0);

        $data = $this->service->getAttendanceHistory($id);

        if ($data === null) {
            $_SESSION['flash_message'] = "Lỗi: Không tìm thấy sinh viên hoặc ID không hợp lệ.";
            header('Location: ' . BASE_URL . '/src/admin/index.php?page=student&action=list');
            exit;
        }

        ob_start();
        include __DIR__ . '/../presentation/attendance/history.php';
        $content = ob_get_clean();
        include __DIR__ . '/../presentation/partials/layout.php';
    }
}