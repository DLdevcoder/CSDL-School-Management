<?php
require_once __DIR__ . '/../inc/db.php';

class ExamRepository {
    public function findAll(): array {
        global $con;
        $exams = [];
        $sql = "SELECT * FROM exam ORDER BY id DESC";
        $result = mysqli_query($con, $sql);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $exams[] = $row;
            }
        }
        return $exams;
    }

    public function delete(int $id): bool {
        global $con;
        $stmt = mysqli_prepare($con, "DELETE FROM exam WHERE id = ?");
        if (!$stmt) {
            return false;
        }
        mysqli_stmt_bind_param($stmt, 'i', $id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return (bool)$ok;
    }

    public function getSubjects(): array {
        global $con;
        $subjects = [];
        $sql = "SELECT subjectName FROM subject ORDER BY subjectName ASC";
        $result = mysqli_query($con, $sql);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $subjects[] = $row;
            }
        }
        return $subjects;
    }

    public function insert(array $data): bool {
        global $con;
        $sql = "INSERT INTO exam (batchName, date, subject, class, totalMark) VALUES (?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($con, $sql);
        if (!$stmt) return false;
        mysqli_stmt_bind_param(
            $stmt, 
            'sssis',
            $data['batchName'],
            $data['date'],
            $data['subject'],
            $data['class'],
            $data['totalMark']
        );

        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return (bool)$ok;
    }
}