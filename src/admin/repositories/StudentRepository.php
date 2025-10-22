<?php
require_once __DIR__ . '/../inc/db.php'; // reuse your existing db connection ($con)

class StudentRepository {
    protected string $imagesDir;

    public function __construct() {
        // images stored previously at src/images/student (relative to src/admin)
        $this->imagesDir = dirname(__DIR__, 2) . '/images/student';
        if (!is_dir($this->imagesDir)) {
            @mkdir($this->imagesDir, 0755, true);
        }
    }

    public function insert(array $data): bool {
        global $con;
        $sql = "INSERT INTO `student`
            (`name`,`address`,`class`,`batch`,`medium`,`gender`,`mobile`,`email`,`school`,`fee`,
             `password`,`subject`,`cexam`,`dob`,`image`,`date`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = mysqli_prepare($con, $sql);
        if (!$stmt) {
            error_log('Prepare failed: ' . mysqli_error($con));
            return false;
        }

        // bind params: s s i i s s s s s s s s s s s
        mysqli_stmt_bind_param(
            $stmt,
            'ssisisssssdssss',
            $data['name'],
            $data['address'],
            $data['class'],
            $data['batch'],
            $data['medium'],
            $data['gender'],
            $data['mobile'],
            $data['email'],
            $data['school'],
            $data['fee'],
            $data['password'],
            $data['subject'],
            $data['cexam'],
            $data['dob'],
            $data['image']
        );

        $ok = mysqli_stmt_execute($stmt);
        if (!$ok) {
            error_log('Insert student failed: ' . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);
        return (bool)$ok;
    }

    // Lấy danh sách course để fill select batch
    public function getCourses(): array {
        global $con;
        $out = [];
        $res = mysqli_query($con, "SELECT course_id, course_name FROM `courses` ORDER BY course_name");
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) $out[] = $r;
        }
        return $out;
    }

    public function getSubjects(): array {
        global $con;
        $out = [];
        $res = mysqli_query($con, "SELECT id, subjectName FROM `subject` ORDER BY subjectName");
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) $out[] = $r;
        }
        return $out;
    }

    public function getCompetitives(): array {
        global $con;
        $out = [];
        $res = mysqli_query($con, "SELECT id, examName FROM `competitive` ORDER BY examName");
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) $out[] = $r;
        }
        return $out;
    }

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

    public function saveImage(array $file): string|false {
        if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) return false;
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION) ?: 'jpg';
        $name = 'student_' . date('YmdHis') . '_' . uniqid() . '.' . preg_replace('/[^a-z0-9]/i', '', $ext);
        $dest = $this->imagesDir . '/' . $name;
        if (move_uploaded_file($file['tmp_name'], $dest)) {
            return $name;
        }
        return false;
    }
}