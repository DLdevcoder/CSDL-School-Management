<?php
require_once __DIR__ . '/../inc/db.php'; // reuse your existing db connection ($con)

class StudentRepository {
    public function findAll() {
        global $con;
        $rows = [];
        $res = mysqli_query($con, "SELECT * FROM student ORDER BY id DESC");
        while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
        return $rows;
    }

    public function findById($id) {
        global $con;
        $id = (int)$id;
        $res = mysqli_query($con, "SELECT * FROM student WHERE id = {$id} LIMIT 1");
        return $res ? mysqli_fetch_assoc($res) : null;
    }

    public function insert(array $data) {
        global $con;
        $name = mysqli_real_escape_string($con, $data['name']);
        return mysqli_query($con, "INSERT INTO student (name) VALUES ('{$name}')");
    }

    public function update($id, array $data) {
        global $con;
        $id = (int)$id;
        $name = mysqli_real_escape_string($con, $data['name']);
        return mysqli_query($con, "UPDATE student SET name='{$name}' WHERE id={$id}");
    }

    public function delete($id) {
        global $con;
        $id = (int)$id;
        return mysqli_query($con, "DELETE FROM student WHERE id={$id}");
    }
}