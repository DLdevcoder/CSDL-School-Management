<?php
require_once __DIR__ . '/../inc/db.php';

class CourseRepository {
    // existing insert(...) ...
    
    public function findAll(): array {
        global $con;
        $out = [];
        $res = mysqli_query($con, "SELECT * FROM `courses` ORDER BY course_id DESC");
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
    public function insert(array $data) {
        global $con;
        $sql = "INSERT INTO `courses` (course_name, course_duration, course_fee, course_start, `class`)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        if (!$stmt) {
            error_log('Prepare failed: ' . mysqli_error($con));
            return false;
        }
        $name = $data['course_name'];
        $duration = $data['course_duration'];
        $fee = $data['course_fee'];
        $start = $data['course_start'];
        $class = (int)$data['class'];

        // bind params: s s d s i  (fee may be string, use 's' if uncertain)
        mysqli_stmt_bind_param($stmt, 'sssii', $name, $duration, $fee, $start, $class);
        $ok = mysqli_stmt_execute($stmt);
        if (!$ok) {
            error_log('Insert course failed: ' . mysqli_stmt_error($stmt));
            mysqli_stmt_close($stmt);
            return false;
        }
        $insertId = mysqli_insert_id($con);
        mysqli_stmt_close($stmt);
        return $insertId ?: true;
    }
}
?>