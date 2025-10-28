<?php
require_once __DIR__ . '/../inc/db.php';

class GalleryRepository {
    protected string $imagesDir;

    public function __construct() {
        $this->imagesDir = dirname(__DIR__, 2) . '/images/gallery';
        if (!is_dir($this->imagesDir)) {
            @mkdir($this->imagesDir, 0755, true);
        }
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

    public function insert(array $data): bool {
        global $con;
        $sql = "INSERT INTO gallery (gallery_title, gallery_image) VALUES (?, ?)";
        
        $stmt = mysqli_prepare($con, $sql);
        if (!$stmt) return false;
        
        mysqli_stmt_bind_param($stmt, 'ss', $data['title'], $data['image']);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return (bool)$ok;
    }

    public function saveImage(array $file): string|false {
        if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return false;
        }
        
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION) ?: 'jpg';
        $safeExt = preg_replace('/[^a-zA-Z0-9]/', '', $ext); // Làm sạch đuôi file
        $filename = 'GalleryImg_' . date('YmdHis') . '_' . uniqid() . '.' . $safeExt;
        
        $destination = $this->imagesDir . '/' . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $filename;
        }
        
        return false;
    }
}