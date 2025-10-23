<?php
require_once __DIR__ . '/../inc/db.php';

class CourseRepository {
    public function findAll(): array {
        global $con;
        $out = [];
        $sql = "SELECT * FROM `courses` ORDER BY course_id DESC";
        $res = mysqli_query($con, $sql);
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) $out[] = $r;
        }
        return $out;
    }

    public function delete(int $id): bool {
        global $con;
        $stmt = mysqli_prepare($con, "DELETE FROM `courses` WHERE course_id = ?");
        if (!$stmt) return false;
        mysqli_stmt_bind_param($stmt, 'i', $id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return (bool)$ok;
    }

    public function countStudentsForCourse(int $courseId): int {
        global $con;
        $stmt = mysqli_prepare($con, "SELECT COUNT(*) AS c FROM `student` WHERE `batch` = ?");
        if (!$stmt) return 0;
        mysqli_stmt_bind_param($stmt, 'i', $courseId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);
        return (int)($row['c'] ?? 0);
    }
}
?>