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
}
?>