<?php
require_once __DIR__ . '/../inc/db.php';

class GalleryRepository {
    protected string $imagesDir;

    public function __construct() {
        $this->imagesDir = dirname(__DIR__, 2) . '/images/gallery';
    }

    public function findAll(): array {
        global $con;
        $items = [];
        $sql = "SELECT * FROM gallery ORDER BY gallery_id DESC";
        $result = mysqli_query($con, $sql);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $items[] = $row;
            }
        }
        return $items;
    }

    public function findById(int $id): ?array {
        global $con;
        $stmt = mysqli_prepare($con, "SELECT * FROM gallery WHERE gallery_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $item = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $item ?: null;
    }

    public function delete(int $id): bool {
        global $con;
        $stmt = mysqli_prepare($con, "DELETE FROM gallery WHERE gallery_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return (bool)$ok;
    }

    public function deleteImageFile(string $filename): bool {
        if (empty($filename)) return false;

        $path = $this->imagesDir . '/' . $filename;
        if (file_exists($path)) {
            return @unlink($path);
        }
        return true;
    }
}