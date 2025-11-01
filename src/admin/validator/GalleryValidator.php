<?php
class GalleryValidator {

    // ✅ Kiểm tra ID ảnh
    public static function validateId($id): ?string {
        if ($id <= 0) {
            return "ID không hợp lệ.";
        }
        return null;
    }

    // ✅ Kiểm tra tiêu đề ảnh
    public static function validateTitle($title): ?string {
        if (empty(trim($title))) {
            return "Tiêu đề không được để trống.";
        }
        return null;
    }

    // ✅ Kiểm tra file upload
    public static function validateImageFile($file): ?string {
        if (empty($file['name'])) {
            return "Bạn phải chọn một file ảnh.";
        }

        // Kiểm tra định dạng file
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            return "Định dạng file ảnh không hợp lệ. Chỉ chấp nhận JPG, PNG, GIF, WEBP.";
        }

        // Kiểm tra kích thước (ví dụ 5MB)
        $maxSize = 5 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            return "Kích thước file ảnh vượt quá 5MB.";
        }

        return null;
    }
}
?>
