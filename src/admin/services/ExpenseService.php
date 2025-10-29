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
}