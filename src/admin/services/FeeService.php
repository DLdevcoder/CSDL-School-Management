<?php
require_once __DIR__ . '/../repositories/FeeRepository.php';

class FeeService {
    protected FeeRepository $repo;

    public function __construct() {
        $this->repo = new FeeRepository();
    }

    public function getAllFeeRecords(): array {
        return $this->repo->findAllWithDetails();
    }

    public function deleteFeeRecord(int $id) {
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