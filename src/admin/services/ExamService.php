<?php
require_once __DIR__ . '/../repositories/ExamRepository.php';
require_once __DIR__ . '/../validator/ExamValidator.php';
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
    
    public function createExam(array $post): bool|string {
        $batchName = trim($post['batchName'] ?? '');
        $subjects = $post['subjects'] ?? [];
        $totalMark = $post['totalMark'] ?? null;
        $date = $post['date'] ?? null;
        $class = (int)($post['class'] ?? 0);

        // ✅ Gọi validator
        $errors = [];
        if ($err = ExamValidator::validateBatchName($batchName)) $errors[] = $err;
        if ($err = ExamValidator::validateSubjects($subjects)) $errors[] = $err;
        if ($err = ExamValidator::validateTotalMark($totalMark)) $errors[] = $err;

        if (!empty($errors)) {
            // Trả về chuỗi lỗi nối lại hoặc mảng tùy cách bạn xử lý ở view
            return implode(' ', $errors);
        }

        $subjectString = implode(', ', $subjects);
        $data = [
            'batchName' => $batchName,
            'date' => $date,
            'subject' => $subjectString,
            'class' => $class,
            'totalMark' => $totalMark
        ];

        $ok = $this->repo->insert($data);
        return $ok ? true : "Lỗi khi lưu vào cơ sở dữ liệu.";
    }
}