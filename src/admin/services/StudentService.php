<?php
require_once __DIR__ . '/../repositories/StudentRepository.php';
require_once __DIR__ . '/../validators/StudentValidator.php';

class StudentService {
    protected StudentRepository $repo;

    public function __construct() {
        $this->repo = new StudentRepository();
    }

    // === Lấy danh sách học sinh ===
    public function getAllStudents(): array {
        return $this->repo->findAllWithCourse();
    }

    // === Lấy thông tin chi tiết học sinh theo ID ===
    public function getStudentById(int $id): ?array {
        return $this->repo->findById($id);
    }

    // === Lấy các danh sách chọn trong form ===
    public function getFormOptions(): array {
        return [
            'courses' => $this->repo->getCourses(),
            'subjects' => $this->repo->getSubjects(),
            'competitives' => $this->repo->getCompetitives(),
        ];
    }

    // === Tạo mới học sinh ===
    public function createStudent(array $post, array $files) {
        $valid = StudentValidator::validateCreate($post, $files);
        if ($valid !== true) return $valid;

        $subject = !empty($post['sub']) ? implode(',', array_map('trim', $post['sub'])) : '';
        $cexam = !empty($post['com']) ? implode(',', array_map('trim', $post['com'])) : '';

        $imageName = null;
        if (!empty($files['u_image']) && !empty($files['u_image']['tmp_name'])) {
            $saved = $this->repo->saveImage($files['u_image']);
            if ($saved === false) return 'Không thể lưu ảnh upload.';
            $imageName = $saved;
        }

        $data = [
            'name' => trim($post['studentName']),
            'address' => trim($post['address'] ?? ''),
            'class' => (int)$post['class'],
            'batch' => (int)($post['batch'] ?? 0),
            'medium' => trim($post['medium'] ?? ''),
            'gender' => trim($post['gender'] ?? ''),
            'mobile' => trim($post['mobile']),
            'email' => trim($post['email']),
            'school' => trim($post['school'] ?? ''),
            'fee' => $post['fee'],
            'password' => password_hash(trim($post['password'] ?? ''), PASSWORD_DEFAULT),
            'subject' => $subject,
            'cexam' => $cexam,
            'dob' => $post['date'] ?? null,
            'image' => $imageName ?? '',
        ];

        return $this->repo->insert($data)
            ? true
            : 'Lỗi khi lưu vào cơ sở dữ liệu.';
    }

    // === Cập nhật học sinh ===
    public function updateStudent(int $id, array $post, array $files) {
        $valid = StudentValidator::validateUpdate($post, $files);
        if ($valid !== true) return $valid;

        $existing = $this->repo->findById($id);
        if (!$existing) return 'Học sinh không tồn tại.';

        $subject = !empty($post['sub']) ? implode(',', array_map('trim', $post['sub'])) : '';
        $cexam = !empty($post['com']) ? implode(',', array_map('trim', $post['com'])) : '';

        $imageName = $existing['image'] ?? '';
        if (!empty($files['u_image']['tmp_name'])) {
            $saved = $this->repo->saveImage($files['u_image']);
            if ($saved === false) return 'Không thể lưu ảnh upload.';
            $imageName = $saved;
        }

        $password = trim($post['password'] ?? '');
        if ($password === '') {
            $password = $existing['password'];
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT);
        }

        $data = [
            'name' => trim($post['studentName']),
            'address' => trim($post['address'] ?? ''),
            'class' => (int)$post['class'],
            'batch' => (int)($post['batch'] ?? 0),
            'medium' => trim($post['medium'] ?? ''),
            'gender' => trim($post['gender'] ?? ''),
            'mobile' => trim($post['mobile']),
            'email' => trim($post['email']),
            'school' => trim($post['school'] ?? ''),
            'fee' => (string)$post['fee'],
            'password' => $password,
            'subject' => $subject,
            'cexam' => $cexam,
            'dob' => $post['date'] ?? null,
            'image' => $imageName,
        ];

        return $this->repo->update($id, $data)
            ? true
            : 'Lỗi khi cập nhật cơ sở dữ liệu.';
    }

    // === Xóa học sinh ===
    public function deleteStudent(int $id) {
        if ($id <= 0) return "ID sinh viên không hợp lệ.";

        $student = $this->repo->findById($id);
        if (!$student) return "Không tìm thấy sinh viên.";

        $deleted = $this->repo->delete($id);
        if (!$deleted) return "Lỗi khi xóa sinh viên khỏi cơ sở dữ liệu.";

        if (!empty($student['image'])) {
            $this->repo->deleteImageFile($student['image']);
        }

        return true;
    }

    // === Xem chi tiết học sinh (hồ sơ + học phí + điểm + chuyên cần) ===
    public function getStudentDetails(int $id): ?array {
        if ($id <= 0) return null;

        $studentProfile = $this->repo->findByIdWithCourse($id);
        if (!$studentProfile) return null;

        $feeHistory = $this->repo->findFeesByStudentId($id);
        $totalPaid = array_sum(array_column($feeHistory, 'fees'));

        return [
            'profile' => $studentProfile,
            'fees' => [
                'history' => $feeHistory,
                'total_paid' => $totalPaid,
                'remaining' => $studentProfile['fee'] - $totalPaid
            ],
            'results' => $this->repo->findResultsByStudentId($id),
            'attendance' => $this->repo->getAttendanceStats($id)
        ];
    }

    // === Thêm học phí cho sinh viên ===
    public function addFeeForStudent(int $studentId, array $postData) {
        $valid = StudentValidator::validateFee($postData);
        if ($valid !== true) return $valid;

        $student = $this->repo->findById($studentId);
        if (!$student) return "Không tìm thấy sinh viên.";

        $data = [
            'studentId' => $studentId,
            'classId' => $student['class'],
            'batchId' => $student['batch'],
            'amount' => (float)$postData['feepaid'],
            'receiptNo' => trim($postData['rNo'])
        ];

        return $this->repo->addFee($data)
            ? true
            : "Lỗi khi thêm học phí.";
    }
}
