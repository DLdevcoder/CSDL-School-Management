<?php
require_once __DIR__ . '/../repositories/ExpenseRepository.php';
require_once __DIR__ . '/../validator/ExpenseValidator.php';

class ExpenseService {
    protected ExpenseRepository $repo;

    public function __construct() {
        $this->repo = new ExpenseRepository();
    }

    public function getAllExpenses(): array {
        return $this->repo->findAll();
    }

    public function deleteExpense(int $id): bool|string {
        // ✅ Dùng Validator để kiểm tra ID
        $error = ExpenseValidator::validateId($id);
        if ($error) return $error;

        $deleted = $this->repo->delete($id);
        if (!$deleted) {
            return "Lỗi khi xóa dữ liệu trong cơ sở dữ liệu.";
        }
        return true;
    }

    public function createExpense(array $post): bool|string {
        $particular = trim($post['particular'] ?? '');
        $amount = $post['amt'] ?? '';
        $date = $post['date'] ?? null;

        // ✅ Gom các lỗi vào một danh sách
        $errors = [];
        if ($err = ExpenseValidator::validateParticular($particular)) $errors[] = $err;
        if ($err = ExpenseValidator::validateAmount($amount)) $errors[] = $err;
        if ($err = ExpenseValidator::validateDate($date)) $errors[] = $err;

        if (!empty($errors)) {
            return implode(' ', $errors);
        }

        $data = [
            'particular' => $particular,
            'date' => $date,
            'amount' => (float)$amount
        ];

        $ok = $this->repo->insert($data);
        return $ok ? true : "Lỗi khi lưu vào cơ sở dữ liệu.";
    }
}
?>
