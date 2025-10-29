<?php
 require_once __DIR__ . '/../inc/db.php';

class FeeRepository {
    public function findAllWithDetails(): array {
        global $con;
        $records = [];
        $sql = "SELECT 
                    f.id, f.fees, f.rNo, f.date,
                    s.name AS student_name, s.class,
                    c.course_name
                FROM fee f
                LEFT JOIN student s ON f.studentID = s.id
                LEFT JOIN courses c ON f.batchID = c.course_id
                ORDER BY f.id DESC";
        
        $result = mysqli_query($con, $sql);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $records[] = $row;
            }
        }
        return $records;
    }

    public function delete(int $id): bool {
        global $con;
        $stmt = mysqli_prepare($con, "DELETE FROM fee WHERE id = ?");
        if (!$stmt) return false;
        
        mysqli_stmt_bind_param($stmt, 'i', $id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return (bool)$ok;
    }
}