<?php
require_once __DIR__ . '/../repositories/StudentRepository.php';
require_once __DIR__ . '/../validator/StudentValidator.php';
require_once __DIR__ . '/../inc/base.php';

class StudentService {
    protected StudentRepository $repo;
    protected StudentValidator $validator;
    protected RedisService $cache;

    public function __construct(StudentRepository $repo, StudentValidator $validator, RedisService $cache) {
        $this->repo = $repo ?? new StudentRepository();
        $this->validator = $validator ?? new StudentValidator();
        $this->cache = $cache; 
    }

    // ============================
    // GET ALL STUDENTS (REDIS CACHE)
    // ============================
    public function getAllStudents(): array {
        $cacheKey = "students:all";

        // 1. Try to get from Redis
        $cached = $this->cache->get($cacheKey);
        if ($cached !== null) {
            return $cached;
        }

        // 2. Get from DB
        $data = $this->repo->findAllWithCourse();

        // 3. Save to Redis cache 60s
        $this->cache->set($cacheKey, $data, 60);

        return $data;
    }

    // ============================
    // GET STUDENT BY ID (REDIS)
    // ============================
    public function getStudentById(int $id): ?array {
        if ($id <= 0) throw new InvalidArgumentException("ID học sinh không hợp lệ");

        $cacheKey = "student:$id";

        // 1. Check Redis cache
        $cached = $this->cache->get($cacheKey);
        if ($cached !== null) {
            return $cached;
        }

        // 2. Get from DB
        $data = $this->repo->findById($id);

        // 3. Cache lại (nếu có)
        if ($data) {
            $this->cache->set($cacheKey, $data, 120);
        }

        return $data;
    }

    // ============================
    // GET FORM OPTIONS (CACHED)
    // ============================
    public function getFormOptions(): array {
        $cacheKey = "form:options";

        // 1. Redis
        $cached = $this->cache->get($cacheKey);
        if ($cached !== null) return $cached;

        // 2. DB
        $data = [
            'courses' => $this->repo->getCourses(),
            'subjects' => $this->repo->getSubjects(),
            'competitives' => $this->repo->getCompetitives(),
        ];

        // 3. Cache 5 phút
        $this->cache->set($cacheKey, $data, 300);

        return $data;
    }

    // ============================
    // CREATE STUDENT (CLEAR CACHE)
    // ============================
    public function createStudent(array $post, array $files): bool {
        if (!validateCsrfToken($post['csrf_token'] ?? '')) {
            throw new Exception("Token bảo mật không hợp lệ");
        }

        $sanitizedPost = $this->sanitizeStudentData($post);
        $valid = $this->validator->validateCreate($sanitizedPost, $files);
        if ($valid !== true) throw new Exception($valid);

        $subject = !empty($sanitizedPost['sub']) ? implode(',', array_map('trim', $sanitizedPost['sub'])) : '';
        $cexam = !empty($sanitizedPost['com']) ? implode(',', array_map('trim', $sanitizedPost['com'])) : '';

        $imageName = null;
        if (!empty($files['u_image']['tmp_name'])) {
            $saved = $this->repo->saveImage($files['u_image']);
            if (!$saved) throw new Exception("Không thể lưu ảnh");
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
        if (!$result) throw new Exception("Không thể lưu dữ liệu");

        // CLEAR CACHE
        $this->cache->delete("students:all");

        return true;
    }

    // ============================
    // UPDATE STUDENT
    // ============================
    public function updateStudent(int $id, array $post, array $files): bool {
        if ($id <= 0) throw new InvalidArgumentException("ID không hợp lệ");
        if (!validateCsrfToken($post['csrf_token'] ?? '')) throw new Exception("Token bảo mật không hợp lệ");

        $sanitizedPost = $this->sanitizeStudentData($post);
        $valid = $this->validator->validateUpdate($sanitizedPost, $files);
        if ($valid !== true) throw new Exception($valid);

        $existing = $this->repo->findById($id);
        if (!$existing) throw new Exception("Học sinh không tồn tại");

        $subject = !empty($sanitizedPost['sub']) ? implode(',', array_map('trim', $sanitizedPost['sub'])) : '';
        $cexam = !empty($sanitizedPost['com']) ? implode(',', array_map('trim', $sanitizedPost['com'])) : '';

        $imageName = $existing['image'];
        if (!empty($files['u_image']['tmp_name'])) {
            $saved = $this->repo->saveImage($files['u_image']);
            if (!$saved) throw new Exception("Không thể lưu ảnh");
            $imageName = $saved;
        }

        $password = $sanitizedPost['password'] === '' 
            ? $existing['password']
            : password_hash($sanitizedPost['password'], PASSWORD_DEFAULT);

        $data = [
            'name' => $sanitizedPost['studentName'],
            'address' => $sanitizedPost['address'],
            'class' => (int)$sanitizedPost['class'],
            'batch' => (int)$sanitizedPost['batch'],
            'medium' => $sanitizedPost['medium'],
            'gender' => $sanitizedPost['gender'],
            'mobile' => $sanitizedPost['mobile'],
            'email' => $sanitizedPost['email'],
            'school' => $sanitizedPost['school'],
            'fee' => (float)$sanitizedPost['fee'],
            'password' => $password,
            'subject' => $subject,
            'cexam' => $cexam,
            'dob' => $sanitizedPost['date'],
            'image' => $imageName,
        ];

        $updated = $this->repo->update($id, $data);
        if (!$updated) throw new Exception("Lỗi cập nhật");

        // CLEAR CACHE
        $this->cache->delete("students:all");
        $this->cache->delete("student:$id");

        return true;
    }

    // ============================
    // DELETE STUDENT
    // ============================
    public function deleteStudent(int $id): bool {
        if ($id <= 0) throw new InvalidArgumentException("ID không hợp lệ");

        $student = $this->repo->findById($id);
        if (!$student) throw new Exception("Không tìm thấy sinh viên");

        $deleted = $this->repo->delete($id);
        if (!$deleted) throw new Exception("Lỗi khi xóa");

        if (!empty($student['image'])) {
            $this->repo->deleteImageFile($student['image']);
        }

        // CLEAR CACHE
        $this->cache->delete("students:all");
        $this->cache->delete("student:$id");

        return true;
    }

    // ============================
    // SANITIZE INPUT
    // ============================
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
