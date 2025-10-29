<?php
require_once __DIR__ . '/../repositories/StudentRepository.php';

class StudentService {
    protected $repo;
    public function __construct() {
        $this->repo = new StudentRepository();
    }

    public function getAllStudents() {
        return $this->repo->findAllWithCourse();
    }

    public function getStudentById($id) {
        return $this->repo->findById($id);
    }

    public function getFormOptions(): array {
        return [
            'courses' => $this->repo->getCourses(),
            'subjects' => $this->repo->getSubjects(),
            'competitives' => $this->repo->getCompetitives(),
        ];
    }

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

        $subject = '';
        if (!empty($post['sub']) && is_array($post['sub'])) {
            $subject = implode(',', array_map('trim', $post['sub']));
        }
        $cexam = '';
        if (!empty($post['com']) && is_array($post['com'])) {
            $cexam = implode(',', array_map('trim', $post['com']));
        }

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

    public function updateStudent(int $id, array $post, array $files) {
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

        $subject = '';
        if (!empty($post['sub']) && is_array($post['sub'])) {
            $subject = implode(',', array_map('trim', $post['sub']));
        }
        $cexam = '';
        if (!empty($post['com']) && is_array($post['com'])) {
            $cexam = implode(',', array_map('trim', $post['com']));
        }

        $existing = $this->repo->findById($id);
        if (!$existing) return 'Học sinh không tồn tại.';

        $imageName = $existing['image'] ?? '';
        if (!empty($files['u_image']) && !empty($files['u_image']['tmp_name'])) {
            $saved = $this->repo->saveImage($files['u_image']);
            if ($saved === false) return 'Không thể lưu ảnh upload.';
            $imageName = $saved;
        }

        $password = trim($post['password'] ?? '');
        if ($password === '') {
            $password = $existing['password'] ?? '';
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT);
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
            'fee' => (string)$fee,
            'password' => $password,
            'subject' => $subject,
            'cexam' => $cexam,
            'dob' => $post['date'] ?? null,
            'image' => $imageName,
        ];

        $ok = $this->repo->update($id, $data);
        if (!$ok) return 'Lỗi khi cập nhật cơ sở dữ liệu.';
        return true;
    }

    public function deleteStudent($id) {
        $id = (int)$id;
        if ($id <= 0) {
            return "ID sinh viên không hợp lệ.";
        }
        $student = $this->repo->findById($id);
        if (!$student) {
            return "Không tìm thấy sinh viên.";
        }
        $deleted = $this->repo->delete($id);
        if (!$deleted) {
            return "Lỗi khi xóa sinh viên khỏi cơ sở dữ liệu.";
        }
        if (!empty($student['image'])) {
            $this->repo->deleteImageFile($student['image']);
        }
        
        return true;
    }
}