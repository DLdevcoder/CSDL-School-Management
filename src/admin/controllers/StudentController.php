<?php
require_once __DIR__ . '/../services/StudentService.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/base.php';

class StudentController {
    protected StudentService $service;

    public function __construct(StudentService $service ) {
        $this->service = $service ?? new StudentService();
    }

    public function list() {
        try {
            requireAdmin();
            
            // Xử lý xóa nếu có parameter
            if (isset($_GET['del'])) {  
                $id = (int)$_GET['del'];
                $this->handleDelete($id);
            }

            $students = $this->service->getAllStudents();
            $this->renderView('student/list.php', ['students' => $students]);
            
        } catch (Exception $e) {
            $this->handleError($e->getMessage(), 'student/list');
        }
    }

    public function create() {
        try {
            requireAdmin();
            
            $error = null;
            $options = $this->service->getFormOptions();

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->handleCreateStudent($_POST, $_FILES);
            }

            $this->renderView('student/create.php', [
                'options' => $options,
                'error' => $error,
                'csrf_token' => generateCsrfToken()
            ]);
            
        } catch (Exception $e) {
            $this->handleError($e->getMessage(), 'student/create');
        }
    }

    public function edit() {
        try {
            requireAdmin();

            $id = $this->getStudentIdFromRequest();
            $student = $this->service->getStudentById($id);
            
            if (!$student) {
                throw new Exception("Học sinh không tồn tại");
            }

            $error = null;
            $options = $this->service->getFormOptions();

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->handleUpdateStudent($id, $_POST, $_FILES);
            }

            $this->renderView('student/edit.php', [
                'student' => $student,
                'options' => $options,
                'error' => $error,
                'csrf_token' => generateCsrfToken()
            ]);
            
        } catch (Exception $e) {
            $this->handleError($e->getMessage(), 'student/list');
        }
    }

    public function view() {
        try {
            requireAdmin();
            
            $id = $this->getStudentIdFromRequest();
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addFees'])) {
                $this->handleAddFee($id, $_POST);
            }

            $studentDetails = $this->service->getStudentDetails($id);
            
            $this->renderView('student/view.php', [
                'studentDetails' => $studentDetails,
                'csrf_token' => generateCsrfToken()
            ]);
            
        } catch (Exception $e) {
            $this->handleError($e->getMessage(), 'student/list');
        }
    }

    // === PRIVATE HELPER METHODS ===

    private function handleDelete(int $id): void {
        try {
            $this->service->deleteStudent($id);
            setSuccess("Đã xóa sinh viên thành công!");
        } catch (Exception $e) {
            setError("Lỗi: " . $e->getMessage());
        }
        
        $this->redirectToList();
    }

    private function handleCreateStudent(array $post, array $files): void {
        $result = $this->service->createStudent($post, $files);
        if ($result) {
            setSuccess("Tạo học sinh thành công!");
            $this->redirectToList();
        }
    }

    private function handleUpdateStudent(int $id, array $post, array $files): void {
        $result = $this->service->updateStudent($id, $post, $files);
        if ($result) {
            setSuccess("Cập nhật học sinh thành công!");
            $this->redirectToList();
        }
    }

    private function handleAddFee(int $studentId, array $postData): void {
        try {
            $this->service->addFeeForStudent($studentId, $postData);
            setSuccess("Thêm học phí thành công!");
        } catch (Exception $e) {
            setError("Lỗi: " . $e->getMessage());
        }
        
        $this->redirectToView($studentId);
    }

    private function getStudentIdFromRequest(): int {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            throw new InvalidArgumentException("ID học sinh không hợp lệ");
        }
        return $id;
    }

    private function renderView(string $viewPath, array $data = []): void {
        ob_start();
        extract($data);
        include __DIR__ . '/../presentation/' . $viewPath;
        $content = ob_get_clean();
        include __DIR__ . '/../presentation/partials/layout.php';
    }

    private function handleError(string $message, string $redirectPage = 'student/list'): void {
        error_log("StudentController Error: " . $message);
        setError($message);
        
        if (strpos($redirectPage, 'list') !== false) {
            $this->redirectToList();
        } else {
            header('Location: ' . BASE_URL . '/src/admin/index.php?page=' . $redirectPage);
            exit;
        }
    }

    private function redirectToList(): void {
        header('Location: ' . BASE_URL . '/src/admin/index.php?page=student&action=list');
        exit;
    }

    private function redirectToView(int $studentId): void {
        header('Location: ' . BASE_URL . '/src/admin/index.php?page=student&action=view&id=' . $studentId);
        exit;
    }
}