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

    public function insert(array $data): bool {
        global $con;
        $sql = "INSERT INTO courses (course_name, course_duration, course_fee, course_start, class) VALUES (?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($con, $sql);
        if (!$stmt) {
            error_log('Prepare insert course failed: ' . mysqli_error($con));
            return false;
        }
        mysqli_stmt_bind_param(
            $stmt, 
            'ssssi',
            $data['course_name'],
            $data['duration'],
            $data['fee'],
            $data['date'],
            $data['class']
        );

        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return (bool)$ok;
    }

    public function findById(int $id): ?array {
        global $con;
        $stmt = mysqli_prepare($con, "SELECT * FROM courses WHERE course_id = ? LIMIT 1");
        if (!$stmt) {
            return null;
        }
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $course = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $course ?: null;
    }

    public function update(int $id, array $data): bool {
        global $con;
        $sql = "UPDATE courses SET 
                    course_name = ?,
                    course_duration = ?,
                    course_fee = ?,
                    course_start = ?,
                    class = ?
                WHERE course_id = ?";
        
        $stmt = mysqli_prepare($con, $sql);
        if (!$stmt) {
            error_log('Prepare update course failed: ' . mysqli_error($con));
            return false;
        }
        mysqli_stmt_bind_param(
            $stmt,
            'ssssii',
            $data['course_name'],
            $data['duration'],
            $data['fee'],
            $data['date'],
            $data['class'],
            $id
        );

        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return (bool)$ok;
    }
}
?>