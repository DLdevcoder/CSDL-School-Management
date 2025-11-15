<?php
require_once __DIR__ . '/../repositories/StudentRepository.php';
require_once __DIR__ . '/../validator/StudentValidator.php';
require_once __DIR__ . '/../inc/base.php'; // Thêm base để dùng helper functions

class StudentService {
    protected StudentRepository $repo;
    protected StudentValidator $validator;

    public function __construct(StudentRepository $repo , StudentValidator $validator) {
        $this->repo = $repo ?? new StudentRepository();
        $this->validator = $validator ?? new StudentValidator();
    }

    // === Lấy danh sách học sinh ===
    public function getAllStudents(): array {
        try {
            return $this->repo->findAllWithCourse();
        } catch (Exception $e) {
            error_log("StudentService::getAllStudents - Error: " . $e->getMessage());
            throw new Exception("Không thể lấy danh sách học sinh");
        }
    }

    // === Lấy thông tin chi tiết học sinh theo ID ===
    public function getStudentById(int $id): ?array {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID học sinh không hợp lệ");
        }
        
        try {
            return $this->repo->findById($id);
        } catch (Exception $e) {
            error_log("StudentService::getStudentById - Error: " . $e->getMessage());
            return null;
        }
    }

    // === Lấy các danh sách chọn trong form ===
    public function getFormOptions(): array {
        try {
            return [
                'courses' => $this->repo->getCourses(),
                'subjects' => $this->repo->getSubjects(),
                'competitives' => $this->repo->getCompetitives(),
            ];
        } catch (Exception $e) {
            error_log("StudentService::getFormOptions - Error: " . $e->getMessage());
            return ['courses' => [], 'subjects' => [], 'competitives' => []];
        }
    }

    // === Tạo mới học sinh ===
    public function createStudent(array $post, array $files): bool {
        // CSRF protection
        if (!validateCsrfToken($post['csrf_token'] ?? '')) {
            throw new Exception("Token bảo mật không hợp lệ");
        }

        // Sanitize input data
        $sanitizedPost = $this->sanitizeStudentData($post);
        
        $valid = $this->validator->validateCreate($sanitizedPost, $files);
        if ($valid !== true) {
            throw new Exception($valid);
        }

        $subject = !empty($sanitizedPost['sub']) ? implode(',', array_map('trim', $sanitizedPost['sub'])) : '';
        $cexam = !empty($sanitizedPost['com']) ? implode(',', array_map('trim', $sanitizedPost['com'])) : '';

        $imageName = null;
        if (!empty($files['u_image']) && !empty($files['u_image']['tmp_name'])) {
            $saved = $this->repo->saveImage($files['u_image']);
            if ($saved === false) throw new Exception('Không thể lưu ảnh upload.');
            $imageName = $saved;
        }

        $data = [
            'name' => $sanitizedPost['studentName'],
            'address' => $sanitizedPost['address'] ?? '',
            'class' => (int)$sanitizedPost['class'],
            'batch' => (int)($sanitizedPost['batch'] ?? 0),
            'medium' => $sanitizedPost['medium'] ?? '',
            'gender' => $sanitizedPost['gender'] ?? '',
            'mobile' => $sanitizedPost['mobile'],
            'email' => $sanitizedPost['email'],
            'school' => $sanitizedPost['school'] ?? '',
            'fee' => (float)$sanitizedPost['fee'],
            'password' => password_hash($sanitizedPost['password'], PASSWORD_DEFAULT),
            'subject' => $subject,
            'cexam' => $cexam,
            'dob' => $sanitizedPost['date'] ?? null,
            'image' => $imageName ?? '',
        ];

        $result = $this->repo->insert($data);
        if (!$result) {
            throw new Exception('Lỗi khi lưu vào cơ sở dữ liệu.');
        }

        return true;
    }

    // === Cập nhật học sinh ===
    public function updateStudent(int $id, array $post, array $files): bool {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID học sinh không hợp lệ");
        }

        // CSRF protection
        if (!validateCsrfToken($post['csrf_token'] ?? '')) {
            throw new Exception("Token bảo mật không hợp lệ");
        }

        // Sanitize input data
        $sanitizedPost = $this->sanitizeStudentData($post);
        
        $valid = $this->validator->validateUpdate($sanitizedPost, $files);
        if ($valid !== true) throw new Exception($valid);

        $existing = $this->repo->findById($id);
        if (!$existing) throw new Exception('Học sinh không tồn tại.');

        $subject = !empty($sanitizedPost['sub']) ? implode(',', array_map('trim', $sanitizedPost['sub'])) : '';
        $cexam = !empty($sanitizedPost['com']) ? implode(',', array_map('trim', $sanitizedPost['com'])) : '';

        $imageName = $existing['image'] ?? '';
        if (!empty($files['u_image']['tmp_name'])) {
            $saved = $this->repo->saveImage($files['u_image']);
            if ($saved === false) throw new Exception('Không thể lưu ảnh upload.');
            $imageName = $saved;
        }

        $password = $sanitizedPost['password'] ?? '';
        if ($password === '') {
            $password = $existing['password'];
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT);
        }

        $data = [
            'name' => $sanitizedPost['studentName'],
            'address' => $sanitizedPost['address'] ?? '',
            'class' => (int)$sanitizedPost['class'],
            'batch' => (int)($sanitizedPost['batch'] ?? 0),
            'medium' => $sanitizedPost['medium'] ?? '',
            'gender' => $sanitizedPost['gender'] ?? '',
            'mobile' => $sanitizedPost['mobile'],
            'email' => $sanitizedPost['email'],
            'school' => $sanitizedPost['school'] ?? '',
            'fee' => (string)$sanitizedPost['fee'],
            'password' => $password,
            'subject' => $subject,
            'cexam' => $cexam,
            'dob' => $sanitizedPost['date'] ?? null,
            'image' => $imageName,
        ];

        $result = $this->repo->update($id, $data);
        if (!$result) {
            throw new Exception('Lỗi khi cập nhật cơ sở dữ liệu.');
        }

        return true;
    }

    // === Xóa học sinh ===
    public function deleteStudent(int $id): bool {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID sinh viên không hợp lệ");
        }

        $student = $this->repo->findById($id);
        if (!$student) {
            throw new Exception("Không tìm thấy sinh viên");
        }

        $deleted = $this->repo->delete($id);
        if (!$deleted) {
            throw new Exception("Lỗi khi xóa sinh viên khỏi cơ sở dữ liệu");
        }

        if (!empty($student['image'])) {
            $this->repo->deleteImageFile($student['image']);
        }

        return true;
    }

    // === Xem chi tiết học sinh ===
    public function getStudentDetails(int $id): array {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID học sinh không hợp lệ");
        }

        $studentProfile = $this->repo->findByIdWithCourse($id);
        if (!$studentProfile) {
            throw new Exception("Không tìm thấy hồ sơ học sinh");
        }

        try {
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
        } catch (Exception $e) {
            error_log("StudentService::getStudentDetails - Error: " . $e->getMessage());
            throw new Exception("Không thể lấy thông tin chi tiết học sinh");
        }
    }

    // === Thêm học phí cho sinh viên ===
    public function addFeeForStudent(int $studentId, array $postData): bool {
        // CSRF protection
        if (!validateCsrfToken($postData['csrf_token'] ?? '')) {
            throw new Exception("Token bảo mật không hợp lệ");
        }

        $valid = $this->validator->validateFee($postData);
        if ($valid !== true) throw new Exception($valid);

        $student = $this->repo->findById($studentId);
        if (!$student) throw new Exception("Không tìm thấy sinh viên");

        $data = [
            'studentId' => $studentId,
            'classId' => $student['class'],
            'batchId' => $student['batch'],
            'amount' => (float)$postData['feepaid'],
            'receiptNo' => trim($postData['rNo'])
        ];

        $result = $this->repo->addFee($data);
        if (!$result) {
            throw new Exception("Lỗi khi thêm học phí");
        }

        return true;
    }

    // === Sanitize student data ===
    private function sanitizeStudentData(array $data): array {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = array_map('sanitizeInput', $value);
            } else {
                $sanitized[$key] = sanitizeInput($value);
            }
        }
        
        return $sanitized;
    }
}