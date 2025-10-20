<?php
require_once __DIR__ . '/../repositories/StudentRepository.php';

class StudentService {
    protected $repo;
    public function __construct() {
        $this->repo = new StudentRepository();
    }

    public function getAllStudents() {
        return $this->repo->findAll();
    }

    public function getStudentById($id) {
        return $this->repo->findById($id);
    }

    public function createStudent(array $data) {
        // simple validation/sanitization example
        $name = trim($data['name'] ?? '');
        if ($name === '') return false;
        return $this->repo->insert(['name' => $name]);
    }

    public function updateStudent(array $data) {
        $id = (int)($data['id'] ?? 0);
        $name = trim($data['name'] ?? '');
        if ($id <= 0 || $name === '') return false;
        return $this->repo->update($id, ['name' => $name]);
    }

    public function deleteStudent($id) {
        return $this->repo->delete((int)$id);
    }
}