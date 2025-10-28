<?php
require_once __DIR__ . '/../repositories/AttendanceRepository.php';

class AttendanceService {
    protected AttendanceRepository $repo;

    public function __construct() {
        $this->repo = new AttendanceRepository();
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
}