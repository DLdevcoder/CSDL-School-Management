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
}
if (php_sapi_name() !== 'cli' && basename($_SERVER['SCRIPT_NAME']) === basename(__FILE__)) {
    $ctrl = new CourseController();
    $ctrl->list();
}
?>