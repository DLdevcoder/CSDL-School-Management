<?php
class ExpenseValidator {

    // ✅ Kiểm tra ID chi phí
    public static function validateId($id): ?string {
        if ($id <= 0) {
            return "ID không hợp lệ.";
        }
        return null;
    }

    // ✅ Kiểm tra chi tiết chi phí (particular)
    public static function validateParticular($value): ?string {
        if (empty(trim($value))) {
            return "Chi tiết chi phí không được để trống.";
        }
        return null;
    }

    // ✅ Kiểm tra số tiền
    public static function validateAmount($value): ?string {
        if (!is_numeric($value) || $value < 0) {
            return "Số tiền không hợp lệ.";
        }
        return null;
    }

    // ✅ Kiểm tra ngày (nếu cần)
    public static function validateDate($value): ?string {
        if ($value && !strtotime($value)) {
            return "Ngày không hợp lệ.";
        }
        return null;
    }
}
?>
