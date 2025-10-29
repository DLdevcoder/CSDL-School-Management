<?php
require_once __DIR__ . '/../inc/db.php';

class ExpenseRepository {
    public function findAll(): array {
        global $con;
        $expenses = [];
        $stmt = mysqli_prepare($con, "SELECT * FROM expenses ORDER BY id DESC");
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $expenses[] = $row;
            }
        }
        mysqli_stmt_close($stmt);
        return $expenses;
    }

    public function delete(int $id): bool {
        global $con;
        $stmt = mysqli_prepare($con, "DELETE FROM expenses WHERE id = ?");
        if (!$stmt) {
            return false;
        }
        mysqli_stmt_bind_param($stmt, 'i', $id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return (bool)$ok;
    }

    public function insert(array $data): bool {
        global $con;
        $sql = "INSERT INTO expenses (particular, date, amt) VALUES (?, ?, ?)";
        
        $stmt = mysqli_prepare($con, $sql);
        if (!$stmt) {
            return false;
        }
        mysqli_stmt_bind_param(
            $stmt, 
            'ssd',
            $data['particular'],
            $data['date'],
            $data['amount']
        );

        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return (bool)$ok;
    }
}