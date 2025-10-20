<?php
require_once __DIR__ . '/../repositories/CourseRepository.php';

class CourseService {
    protected $repo;
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

    public function createCourse(array $input) {
        $name = trim($input['courseName'] ?? '');
        $class = (int)($input['class'] ?? 0);
        $duration = trim($input['duration'] ?? '');
        $fee = trim($input['fee'] ?? '');
        $date = trim($input['date'] ?? '');

        if ($name === '') return 'Tên khóa học không được để trống.';
        if ($class <= 0) return 'Lớp không hợp lệ.';
        if ($duration === '') return 'Thời gian không được để trống.';
        if ($fee === '' || !is_numeric($fee)) return 'Học phí phải là số.';
        if ($date === '') return 'Ngày bắt đầu không được để trống.';

        // chuẩn hoá dữ liệu cho repository
        $data = [
            'course_name' => $name,
            'course_duration' => $duration,
            'course_fee' => $fee,
            'course_start' => $date,
            'class' => $class,
        ];

        $res = $this->repo->insert($data);
        if ($res === false) return 'Lỗi lưu dữ liệu vào database.';
        return true;
    }
}
?>
