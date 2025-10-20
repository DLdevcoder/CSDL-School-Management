<?php
require_once __DIR__ . '/../services/StudentService.php';
require_once __DIR__ . '/../inc/auth.php';

class StudentController {
    protected $service;

    public function __construct() {
        $this->service = new StudentService();
    }

    public function list() {
        requireAdmin();

        // Gọi layout đầu trang (chứa BASE_URL, Bootstrap, ... )
        require_once __DIR__ . '/../presentation/partials/top.php';

        // Lấy dữ liệu sinh viên
        $students = $this->service->getAllStudents();

        // --- Bắt đầu bố cục trang ---
        include __DIR__ . '/../presentation/partials/navbar.php';
        echo '<div class="container-fluid">';
        echo '<div class="row mt-1">';
        echo '<div class="col-md-3">';
        include __DIR__ . '/../presentation/partials/sidebar.php';
        echo '</div>'; // end sidebar

        echo '<div class="col-md-9">';
        include __DIR__ . '/../presentation/student/list.php';
        echo '</div>'; // end content
        echo '</div>'; // end row
        echo '</div>'; // end container

        include __DIR__ . '/../presentation/partials/footer.php';
        // --- Kết thúc bố cục trang ---
    }

    public function create() {
        requireAdmin();

        require_once __DIR__ . '/../presentation/partials/top.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->service->createStudent($_POST);
            header('Location: ' . BASE_URL . '/src/admin/index.php?page=student&action=list');
            exit;
        }

        include __DIR__ . '/../presentation/partials/navbar.php';
        echo '<div class="container-fluid"><div class="row mt-1">';
        echo '<div class="col-md-3">';
        include __DIR__ . '/../presentation/partials/sidebar.php';
        echo '</div><div class="col-md-9">';
        include __DIR__ . '/../presentation/student/create.php';
        echo '</div></div></div>';
        include __DIR__ . '/../presentation/partials/footer.php';
    }

    public function edit() {
        requireAdmin();

        require_once __DIR__ . '/../presentation/partials/top.php';
        $id = $_GET['id'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->service->updateStudent($_POST);
            header('Location: ' . BASE_URL . '/src/admin/index.php?page=student&action=list');
            exit;
        }

        $student = $this->service->getStudentById($id);

        include __DIR__ . '/../presentation/partials/navbar.php';
        echo '<div class="container-fluid"><div class="row mt-1">';
        echo '<div class="col-md-3">';
        include __DIR__ . '/../presentation/partials/sidebar.php';
        echo '</div><div class="col-md-9">';
        include __DIR__ . '/../presentation/student/edit.php';
        echo '</div></div></div>';
        include __DIR__ . '/../presentation/partials/footer.php';
    }

    public function delete() {
        requireAdmin();
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->service->deleteStudent($id);
        }
        header('Location: ' . BASE_URL . '/src/admin/index.php?page=student&action=list');
        exit;
    }
}
