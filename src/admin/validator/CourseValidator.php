<?php
 class CourseValidator {
    public static function validateCourseName($courseName) {
        if (empty(trim($courseName))) {
            return "Tên khóa học không được để trống";
        }
        return null;
    }
     public static function validateFee($fee) {
        if (!is_numeric($fee) || $fee < 0) {
            return "Học phí không hợp lệ.";
        }
        return null;
    }
     public static function validateIdCourse($valueID) {
        if ($valueID <= 0) {
            return "ID khóa học không hợp lệ";
        }
        return null;
    }

 }

?>