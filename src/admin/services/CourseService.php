<?php
require_once __DIR__ . '/../repositories/CourseRepository.php';
require_once __DIR__ . '/../validator/CourseValidator.php';

class CourseService {
    protected CourseRepository $repo;

    public function __construct() {
        $this->repo = new CourseRepository();
    }

    public function getAllCourses(): array {
        $courses = $this->repo->findAll();
        foreach ($courses as &$c) {
            $c['student_count'] = $this->repo->countStudentsForCourse((int)$c['course_id']);
        }
        return $courses;
    }

    public function deleteCourse(int $id): bool|string {
        $error = CourseValidator::validateIdCourse($id);
        if ($error) return $error;
        return $this->repo->delete($id);
    }

    public function createCourse(array $post): bool|string {
        $courseName = trim($post['courseName'] ?? '');
        $fee = trim($post['fee'] ?? '');
        $class = (int)($post['class'] ?? 0);
        $duration = trim($post['duration'] ?? '');
        $date = $post['date'] ?? null;

        // ✅ Gọi các hàm validator
        $errors = [];
        if ($err = CourseValidator::validateCourseName($courseName)) $errors[] = $err;
        if ($err = CourseValidator::validateFee($fee)) $errors[] = $err;

        if (!empty($errors)) {
            return implode(' ', $errors); // Trả về tất cả lỗi trong 1 chuỗi
        }

        $data = [
            'course_name' => $courseName,
            'class' => $class,
            'duration' => $duration,
            'fee' => $fee,
            'date' => $date,
        ];

        $ok = $this->repo->insert($data);
        return $ok ? true : 'Đã xảy ra lỗi khi thêm vào cơ sở dữ liệu.';
    }

    public function getCourseById(int $id): ?array {
        $error = CourseValidator::validateIdCourse($id);
        if ($error) return null;
        return $this->repo->findById($id);
    }

    public function updateCourse(int $id, array $post): bool|string {
        $courseName = trim($post['courseName'] ?? '');
        $fee = trim($post['fee'] ?? '');
        $class = (int)($post['class'] ?? 0);
        $duration = trim($post['duration'] ?? '');
        $date = $post['date'] ?? null;

        // ✅ Gọi các validator
        $errors = [];
        if ($err = CourseValidator::validateIdCourse($id)) $errors[] = $err;
        if ($err = CourseValidator::validateCourseName($courseName)) $errors[] = $err;
        if ($err = CourseValidator::validateFee($fee)) $errors[] = $err;

        if (!empty($errors)) {
            return implode(' ', $errors);
        }

        $data = [
            'course_name' => $courseName,
            'class' => $class,
            'duration' => $duration,
            'fee' => $fee,
            'date' => $date,
        ];

        $ok = $this->repo->update($id, $data);
        return $ok ? true : 'Đã xảy ra lỗi khi cập nhật cơ sở dữ liệu.';
    }
}
?>
