<?php
require_once __DIR__ . '/../repositories/ExpenseRepository.php';

class ExpenseService {
    protected ExpenseRepository $repo;

    public function __construct() {
        $this->repo = new ExpenseRepository();
    }

    public function getAllExpenses(): array {
        return $this->repo->findAll();
    }

    public function deleteExpense(int $id) {
        if ($id <= 0) {
            return "ID không hợp lệ.";
        }

        $deleted = $this->repo->delete($id);
        if (!$deleted) {
            return "Lỗi khi xóa dữ liệu trong cơ sở dữ liệu.";
        }

        return true;
    }

    public function createExpense(array $post) {
        $particular = trim($post['particular'] ?? '');
        if ($particular === '') {
            return "Chi tiết chi phí không được để trống.";
        }

        $amount = $post['amt'] ?? '';
        if (!is_numeric($amount) || $amount < 0) {
            return "Số tiền không hợp lệ.";
        }

        $data = [
            'particular' => $particular,
            'date' => $post['date'] ?? null,
            'amount' => (float)$amount
        ];

        $ok = $this->repo->insert($data);
        return $ok ? true : "Lỗi khi lưu vào cơ sở dữ liệu.";
    }
}