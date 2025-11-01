<?php
class ExamValidator {
    public static function validateIdName($id) {
        if ($id < 0 || empty(trim($id))) {
            return "Id bài kiểm tra không hợp lệ";
        }
        return null;
    }
    public static function validateBatchName($value): ?string {
        if (empty(trim($value))) {
            return "Tên khóa học không được để trống.";
        }
        return null;
    }

    // Kiểm tra danh sách môn học
    public static function validateSubjects($subjects): ?string {
        if (empty($subjects) || !is_array($subjects)) {
            return "Bạn phải chọn ít nhất một môn học.";
        }
        return null;
    }

    // Kiểm tra tổng điểm
    public static function validateTotalMark($value): ?string {
        if (!is_numeric($value)) {
            return "Tổng điểm phải là một con số.";
        }
        if ($value < 0) {
            return "Tổng điểm không được âm.";
        }
        return null;
    }
}
?>