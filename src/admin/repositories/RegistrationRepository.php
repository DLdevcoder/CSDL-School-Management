<?php
require_once __DIR__ . '/../inc/db.php';

class RegistrationRepository {
    public function findAll(): array {
        global $con;
        $registrations = [];
        $sql = "SELECT * FROM register ORDER BY regid DESC";
        $result = mysqli_query($con, $sql);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $registrations[] = $row;
            }
        }
        return $registrations;
    }
    public function delete(int $id): bool {
        global $con;
        $stmt = mysqli_prepare($con, "DELETE FROM register WHERE regid = ?");
        if (!$stmt) return false;
        
        mysqli_stmt_bind_param($stmt, 'i', $id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return (bool)$ok;
    }
}