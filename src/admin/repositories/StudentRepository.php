<?php
require_once __DIR__ . '/../inc/db.php';

class StudentRepository
{
    protected string $imagesDir;

    public function __construct()
    {
        $this->imagesDir = dirname(__DIR__, 2) . '/images/student';
        if (!is_dir($this->imagesDir)) {
            @mkdir($this->imagesDir, 0755, true);
        }
    }

    public function insert(array $data): bool
    {
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

    public function findAllWithCourse(): array {
        global $con;
        $students = [];
        $sql = "SELECT s.*, c.course_name 
                FROM student s
                LEFT JOIN courses c ON s.batch = c.course_id
                ORDER BY s.id DESC";
        
        $result = mysqli_query($con, $sql);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $students[] = $row;
            }
        }
        return $students;
    }

    public function deleteImageFile(string $filename): bool {
        if (empty($filename)) return false;
        
        $path = dirname(__DIR__, 2) . '/images/student/' . $filename;
        if (file_exists($path)) {
            return @unlink($path);
        }
        return true;
    }

    public function findById(int $id): ?array
    {
        global $con;
        $stmt = mysqli_prepare($con, "SELECT * FROM `student` WHERE `id` = ? LIMIT 1");
        if (!$stmt) return null;
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);
        return $row ?: null;
    }

    public function update(int $id, array $data): bool
    {
        global $con;
        $sql = "UPDATE `student` SET
                `name` = ?, `address` = ?, `class` = ?, `batch` = ?, `medium` = ?, `gender` = ?,
                `mobile` = ?, `email` = ?, `school` = ?, `fee` = ?, `password` = ?, `subject` = ?,
                `cexam` = ?, `dob` = ?, `image` = ?
                WHERE `id` = ?";
        $stmt = mysqli_prepare($con, $sql);
        if (!$stmt) {
            error_log('Prepare failed: ' . mysqli_error($con));
            return false;
        }

        $types = 'ssii' . str_repeat('s', 11) . 'i';

        $name = $data['name'];
        $address = $data['address'];
        $class = (int)$data['class'];
        $batch = (int)$data['batch'];
        $medium = $data['medium'];
        $gender = $data['gender'];
        $mobile = $data['mobile'];
        $email = $data['email'];
        $school = $data['school'];
        $fee = (string)$data['fee'];
        $password = $data['password'];
        $subject = $data['subject'];
        $cexam = $data['cexam'];
        $dob = $data['dob'];
        $image = $data['image'];

        mysqli_stmt_bind_param(
            $stmt,
            $types,
            $name, $address, $class, $batch,
            $medium, $gender, $mobile, $email, $school, $fee,
            $password, $subject, $cexam, $dob, $image,
            $id
        );

        $ok = mysqli_stmt_execute($stmt);
        if (!$ok) error_log('Update student failed: ' . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return (bool)$ok;
    }

    public function delete($id): bool
    {
        global $con;
        $id = (int)$id;
        $stmt = mysqli_prepare($con, "DELETE FROM student WHERE id = ?");
        if (!$stmt) {
            error_log('Delete prepare failed: ' . mysqli_error($con));
            return false;
        }
        mysqli_stmt_bind_param($stmt, 'i', $id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return (bool)$ok;
    }

    public function getCourses(): array
    {
        global $con;
        $out = [];
        $res = mysqli_query($con, "SELECT course_id, course_name FROM `courses` ORDER BY course_name");
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) $out[] = $r;
        }
        return $out;
    }

    public function getSubjects(): array
    {
        global $con;
        $out = [];
        $res = mysqli_query($con, "SELECT id, subjectName FROM `subject` ORDER BY subjectName");
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) $out[] = $r;
        }
        return $out;
    }

    public function getCompetitives(): array
    {
        global $con;
        $out = [];
        $res = mysqli_query($con, "SELECT id, examName FROM `competitive` ORDER BY examName");
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) $out[] = $r;
        }
        return $out;
    }

    public function saveImage(array $file): string|false
    {
        if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) return false;
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION) ?: 'jpg';
        $ext = preg_replace('/[^a-z0-9]/i', '', $ext);
        $name = 'student_' . date('YmdHis') . '_' . uniqid() . '.' . $ext;
        $dest = $this->imagesDir . '/' . $name;
        if (move_uploaded_file($file['tmp_name'], $dest)) {
            return $name;
        }
        return false;
    }
}