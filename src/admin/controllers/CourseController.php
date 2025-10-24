<?php
require_once __DIR__ . '/../inc/auth.php';   
require_once __DIR__ . '/../inc/base.php'; 
require_once __DIR__ . '/../services/CourseService.php';

class CourseController {
    protected CourseService $service;

    public function __construct() {
        $this->service = new CourseService();
    }

    public function list() {
        requireAdmin();
        if (isset($_GET['del'])) {
            $id = (int)$_GET['del'];
            if ($id > 0) {
                $ok = $this->service->deleteCourse($id);
                if ($ok) {
                    header('Location: ' . BASE_URL . '/src/admin/index.php?page=course&action=list');
                    exit;
                } else {
                    $error = 'Không thể xóa khóa học.';
                }
            }
        }

        $courses = $this->service->getAllCourses();
        ob_start();
        include __DIR__ . '/../presentation/course/list.php';
        $content = ob_get_clean();
        include __DIR__ . '/../presentation/partials/layout.php';
    }

    public function create() {
        requireAdmin();
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->service->createCourse($_POST);
            if ($result === true) {
                $_SESSION['flash_message'] = 'Thêm khóa học thành công!';
                header('Location: ' . BASE_URL . '/src/admin/index.php?page=course&action=list');
                exit;
            } else {
                $error = $result;
            }
        }
        ob_start();
        include __DIR__ . '/../presentation/course/create.php';
        $content = ob_get_clean();
        include __DIR__ . '/../presentation/partials/layout.php';
    }

    public function edit() {
        requireAdmin();
        $error = null;
        $id = (int)($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            header('Location: ' . BASE_URL . '/src/admin/index.php?page=course&action=list');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->service->updateCourse($id, $_POST);
            if ($result === true) {
                $_SESSION['flash_message'] = 'Cập nhật khóa học thành công!';
                header('Location: ' . BASE_URL . '/src/admin/index.php?page=course&action=list');
                exit;
            } else {
                $error = $result;
            }
        }

        $course = $this->service->getCourseById($id);
        if (!$course) {
            $_SESSION['flash_message'] = 'Lỗi: Không tìm thấy khóa học!';
            header('Location: ' . BASE_URL . '/src/admin/index.php?page=course&action=list');
            exit;
        }
        ob_start();
        include __DIR__ . '/../presentation/course/edit.php';
        $content = ob_get_clean();
        include __DIR__ . '/../presentation/partials/layout.php';
    }
}
if (php_sapi_name() !== 'cli' && basename($_SERVER['SCRIPT_NAME']) === basename(__FILE__)) {
    $ctrl = new CourseController();
    $ctrl->list();
}
?>