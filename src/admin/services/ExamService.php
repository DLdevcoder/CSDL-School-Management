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

    public function getFormData(): array {
        return [
            'subjects' => $this->repo->getSubjects()
        ];
    }
    
    public function createExam(array $post) {
        if (empty(trim($post['batchName']))) {
            return "Tên khóa học không được để trống.";
        }
        if (empty($post['subjects']) || !is_array($post['subjects'])) {
            return "Bạn phải chọn ít nhất một môn học.";
        }
        if (!is_numeric($post['totalMark'])) {
            return "Tổng điểm phải là một con số.";
        }
        $subjectString = implode(', ', $post['subjects']);
        $data = [
            'batchName' => trim($post['batchName']),
            'date' => $post['date'],
            'subject' => $subjectString,
            'class' => (int)$post['class'],
            'totalMark' => $post['totalMark']
        ];

        $ok = $this->repo->insert($data);
        return $ok ? true : "Lỗi khi lưu vào cơ sở dữ liệu.";
    }
}