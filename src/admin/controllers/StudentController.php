<?php
require_once __DIR__ . '/../services/StudentService.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/base.php';
class StudentController
{
    protected StudentService $service;

    public function __construct()
    {
        $this->service = new StudentService();
    }

    public function list()
    {
        requireAdmin();
        $students = $this->service->getAllStudents();

        // Render view vào biến $content
        ob_start();
        include __DIR__ . '/../presentation/student/list.php';
        $content = ob_get_clean();

        // Include layout chính, layout này sẽ hiển thị $content
        include __DIR__ . '/../presentation/partials/layout.php';
    }

    public function create()
    {
        requireAdmin();
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $res = $this->service->createStudent($_POST, $_FILES);
            if ($res === true) {
                header('Location: ' . BASE_URL . '/src/admin/index.php?page=student&action=list');
                exit;
            } else {
                $error = $res;
            }
        }

        $options = $this->service->getFormOptions();

        // Render view vào biến $content
        ob_start();
        include __DIR__ . '/../presentation/student/create.php'; // View này cần $options, $error
        $content = ob_get_clean();

        // Include layout chính
        include __DIR__ . '/../presentation/partials/layout.php';
    }

    public function edit()
    {
        requireAdmin();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '/src/admin/index.php?page=student&action=list');
            exit;
        }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Giả sử service->updateStudent cũng trả về true hoặc chuỗi lỗi
            $res = $this->service->updateStudent($_POST, $_FILES);
            if ($res === true) {
                header('Location: ' . BASE_URL . '/src/admin/index.php?page=student&action=list');
                exit;
            } else {
                $error = $res;
            }
        }

        $student = $this->service->getStudentById($id);
        $options = $this->service->getFormOptions();

        // Render view vào biến $content
        ob_start();
        include __DIR__ . '/../presentation/student/edit.php'; // View này cần $student, $options, $error
        $content = ob_get_clean();

        // Include layout chính
        include __DIR__ . '/../presentation/partials/layout.php';
    }

    public function delete()
    {
        requireAdmin();
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->service->deleteStudent($id);
        }
        header('Location: ' . BASE_URL . '/src/admin/index.php?page=student&action=list');
        exit;
    }
}