<?php
require_once __DIR__ . '/../repositories/AttendanceRepository.php';
require_once __DIR__ . '/../repositories/StudentRepository.php';

class AttendanceService {
    protected AttendanceRepository $repo;
    protected StudentRepository $studentRepo;

    public function __construct() {
        $this->repo = new AttendanceRepository();
        $this->studentRepo = new StudentRepository();
    }

    public function getStudentsForAttendance(int $courseId): array {
        if ($courseId <= 0) return [];
        return $this->repo->findStudentsByCourseId($courseId);
    }

    public function recordBulkAttendance(array $postData) {
        $studentIds = $postData['checkboxes'] ?? [];
        if (empty($studentIds)) {
            return "Vui lòng chọn ít nhất một sinh viên.";
        }

        $bulkOption = $postData['bulk-options'] ?? '';
        if ($bulkOption !== 'present' && $bulkOption !== 'absent') {
            return "Vui lòng chọn một hành động hợp lệ (Có mặt/Vắng mặt).";
        }

        $status = ($bulkOption === 'present') ? 'Present' : 'Absent';
        $records = [];
        foreach ($studentIds as $studentId) {
            $records[] = [
                'studentId' => (int)$studentId,
                'status' => $status
            ];
        }

        $ok = $this->repo->saveBulkAttendance($records);
        return $ok ? true : "Lỗi khi lưu dữ liệu điểm danh.";
    }

    public function getAttendanceHistory(int $studentId): ?array {
        if ($studentId <= 0) return null;

        $student = $this->studentRepo->findById($studentId);
        if (!$student) return null;

        $history = $this->repo->findHistoryByStudentId($studentId);

        return [
            'student' => $student,
            'history' => $history
        ];
    }
}