<?php
require_once __DIR__ . '/../repositories/RegistrationRepository.php';

class RegistrationService {
    protected RegistrationRepository $repo;

    public function __construct() {
        $this->repo = new RegistrationRepository();
    }

    public function getAllRegistrations(): array {
        return $this->repo->findAll();
    }

    public function deleteRegistration(int $id) {
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