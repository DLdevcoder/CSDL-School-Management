<?php
require_once __DIR__ . '/../repositories/CourseRepository.php';

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

    public function deleteCourse(int $id): bool {
        if ($id <= 0) return false;
        return $this->repo->delete($id);
    }

    public function createCourse(array $post) {
        $courseName = trim($post['courseName'] ?? '');
        if ($courseName === '') {
            return 'Tên khóa học không được để trống.';
        }
        $fee = trim($post['fee'] ?? '');
        if (!is_numeric($fee) || $fee < 0) {
            return 'Học phí không hợp lệ.';
        }
        $data = [
            'course_name' => $courseName,
            'class' => (int)($post['class'] ?? 0),
            'duration' => trim($post['duration'] ?? ''),
            'fee' => $fee,
            'date' => $post['date'] ?? null,
        ];

        $ok = $this->repo->insert($data);
        
        return $ok ? true : 'Đã xảy ra lỗi khi thêm vào cơ sở dữ liệu.';
    }
}
?>