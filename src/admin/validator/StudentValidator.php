<?php
class StudentValidator {

    public static function validateCreate(array $post, array $files): string|bool {
        $name = trim($post['studentName'] ?? '');
        if ($name === '') return 'Tên học sinh không được để trống.';

        $class = (int)($post['class'] ?? 0);
        if ($class <= 0) return 'Lớp không hợp lệ.';

        $mobile = trim($post['mobile'] ?? '');
        if ($mobile === '') return 'Số điện thoại không được để trống.';

        $email = trim($post['email'] ?? '');
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) return 'Email không hợp lệ.';

        $fee = $post['fee'] ?? '0';
        if (!is_numeric($fee)) return 'Học phí phải là số.';

        if (!empty($files['u_image']) && !empty($files['u_image']['error']) && $files['u_image']['error'] !== UPLOAD_ERR_OK) {
            return 'Lỗi khi upload ảnh.';
        }

        return true;
    }

    public static function validateUpdate(array $post, array $files): string|bool {
        // có thể dùng lại logic của create
        return self::validateCreate($post, $files);
    }

    public static function validateFee(array $post): string|bool {
        if (!is_numeric($post['feepaid'] ?? '') || $post['feepaid'] <= 0) {
            return "Số tiền học phí không hợp lệ.";
        }

        if (empty(trim($post['rNo'] ?? ''))) {
            return "Số biên lai không được để trống.";
        }

        return true;
    }
}
