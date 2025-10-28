<?php
require_once __DIR__ . '/../repositories/ExamRepository.php';

class ExamService {
    protected ExamRepository $repo;

    public function __construct() {
        $this->repo = new ExamRepository();
    }

    public function getAllExams(): array {
        return $this->repo->findAll();
    }

    public function deleteExam(int $id) {
        if ($id <= 0) {
            return "ID không hợp lệ.";
        }

        $deleted = $this->repo->delete($id);
        if (!$deleted) {
            return "Lỗi khi xóa dữ liệu trong cơ sở dữ liệu.";
        }

        return true;
    }
}