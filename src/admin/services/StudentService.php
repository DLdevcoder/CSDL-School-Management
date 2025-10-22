<?php
require_once __DIR__ . '/../repositories/StudentRepository.php';

class StudentService {
    protected $repo;
    public function __construct() {
        $this->repo = new StudentRepository();
    }

    public function getAllStudents() {
        return $this->repo->findAll();
    }

    public function getStudentById($id) {
        return $this->repo->findById($id);
    }

    /**
     * Trả về dữ liệu cần cho form (courses, subjects, competitive)
     * [Hàm này được thêm từ file mẫu]
     */
    public function getFormOptions(): array {
        return [
            'courses' => $this->repo->getCourses(),
            'subjects' => $this->repo->getSubjects(),
            'competitives' => $this->repo->getCompetitives(),
        ];
    }

    /**
     * Tạo học sinh mới. Trả về true khi OK, chuỗi lỗi khi fail.
     * $post: $_POST, $files: $_FILES
     * [Logic của hàm này được thay thế hoàn toàn từ file mẫu]
     */
    public function createStudent(array $post, array $files) {
        // basic validation
        $name = trim($post['studentName'] ?? '');
        if ($name === '') return 'Tên học sinh không được để trống.';
        $class = (int)($post['class'] ?? 0);
        if ($class <= 0) return 'Lớp không hợp lệ.';
        $batch = (int)($post['batch'] ?? 0);
        $mobile = trim($post['mobile'] ?? '');
        if ($mobile === '') return 'Số điện thoại không được để trống.';
        $email = trim($post['email'] ?? '');
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) return 'Email không hợp lệ.';
        $fee = $post['fee'] ?? '0';
        if (!is_numeric($fee)) return 'Học phí phải là số.';
        $password = trim($post['password'] ?? '');

        // subjects / competitives may come as arrays
        $subject = '';
        if (!empty($post['sub']) && is_array($post['sub'])) {
            $subject = implode(',', array_map('trim', $post['sub']));
        }
        $cexam = '';
        if (!empty($post['com']) && is_array($post['com'])) {
            $cexam = implode(',', array_map('trim', $post['com']));
        }

        // image handling
        $imageName = null;
        if (!empty($files['u_image'])) {
            $saved = $this->repo->saveImage($files['u_image']);
            if ($saved === false) {
                return 'Không thể lưu ảnh upload.';
            }
            $imageName = $saved;
        } else {
            $imageName = null;
        }

        $data = [
            'name' => $name,
            'address' => trim($post['address'] ?? ''),
            'class' => $class,
            'batch' => $batch,
            'medium' => trim($post['medium'] ?? ''),
            'gender' => trim($post['gender'] ?? ''),
            'mobile' => $mobile,
            'email' => $email,
            'school' => trim($post['school'] ?? ''),
            'fee' => $fee,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'subject' => $subject,
            'cexam' => $cexam,
            'dob' => $post['date'] ?? null,
            'image' => $imageName ?? '',
        ];

        $ok = $this->repo->insert($data);
        if (!$ok) return 'Lỗi khi lưu vào cơ sở dữ liệu.';
        return true;
    }

    // [Hàm này được giữ nguyên từ file gốc của bạn]
    public function updateStudent(array $data) {
        $id = (int)($data['id'] ?? 0);
        $name = trim($data['name'] ?? '');
        if ($id <= 0 || $name === '') return false;
        return $this->repo->update($id, ['name' => $name]);
    }

    // [Hàm này được giữ nguyên từ file gốc của bạn]
    public function deleteStudent($id) {
        return $this->repo->delete((int)$id);
    }
}