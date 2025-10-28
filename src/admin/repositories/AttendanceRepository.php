<?php
require_once __DIR__ . '/../inc/db.php';

class AttendanceRepository {

    public function findStudentsByCourseId(int $courseId): array {
        global $con;
        $students = [];
        $sql = "SELECT s.*, c.course_name 
                FROM student s
                LEFT JOIN courses c ON s.batch = c.course_id
                WHERE s.batch = ?";
        
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $courseId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $students[] = $row;
            }
        }
        mysqli_stmt_close($stmt);
        return $students;
    }

    public function saveBulkAttendance(array $records): bool {
        global $con;
        $sql = "INSERT INTO attendance (studentId, attendance, date) VALUES ";
        
        $placeholders = [];
        $values = [];
        $types = "";

        foreach ($records as $record) {
            $placeholders[] = '(?, ?, NOW())';
            $values[] = $record['studentId'];
            $values[] = $record['status'];
            $types .= "is";
        }

        $sql .= implode(', ', $placeholders);
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, $types, ...$values);

        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }
}