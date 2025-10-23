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
        ob_start();
        include __DIR__ . '/../presentation/student/list.php';
        $content = ob_get_clean();
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
        ob_start();
        include __DIR__ . '/../presentation/student/create.php'; 
        $content = ob_get_clean();
        include __DIR__ . '/../presentation/partials/layout.php';
    }

    public function edit() {
        requireAdmin();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            header('Location: ' . BASE_URL . '/src/admin/index.php?page=student&action=list');
            exit;
        }

        $student = $this->service->getStudentById($id);
        if (!$student) {
            header('Location: ' . BASE_URL . '/src/admin/index.php?page=student&action=list');
            exit;
        }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $res = $this->service->updateStudent($id, $_POST, $_FILES);
            if ($res === true) {
                header('Location: ' . BASE_URL . '/src/admin/index.php?page=student&action=list');
                exit;
            } else {
                $error = $res;
                // refresh student data to show current DB values (except posted)
                $student = $this->service->getStudentById($id);
            }
        }

        $options = $this->service->getFormOptions();

        ob_start();
        include __DIR__ . '/../presentation/student/edit.php'; 
        $content = ob_get_clean();
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