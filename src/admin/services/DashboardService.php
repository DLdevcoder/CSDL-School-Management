<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../repositories/AdminStatsRepository.php';

class DashboardService {
    protected $statsRepo;

    public function __construct() {
        $this->statsRepo = new AdminStatsRepository();
    }

    /**
     * Trả về số học sinh theo lớp (1..$maxClass)
     * Kết quả: [classNumber => count, ...]
     */
    public function getStudentsPerClass(int $maxClass = 10): array {
        global $con;
        $out = [];
        $maxClass = max(1, $maxClass);
        $stmt = null;
        for ($i = 1; $i <= $maxClass; $i++) {
            $class = (int)$i;
            $sql = "SELECT COUNT(*) AS c FROM `student` WHERE `class` = {$class}";
            $res = mysqli_query($con, $sql);
            $row = $res ? mysqli_fetch_assoc($res) : null;
            $out[$class] = (int)($row['c'] ?? 0);
        }
        return $out;
    }

    /**
     * Trả về tổng phí dự kiến (từ bảng student), tổng phí đã nộp (bảng fee)
     */
    public function getTotalFees(): array {
        global $con;
        $total_expected = 0.0;
        $total_paid = 0.0;

        $res1 = mysqli_query($con, "SELECT SUM(`fee`) AS total_expected FROM `student`");
        if ($res1) {
            $r1 = mysqli_fetch_assoc($res1);
            $total_expected = (float)($r1['total_expected'] ?? 0);
        }

        $res2 = mysqli_query($con, "SELECT SUM(`fees`) AS total_paid FROM `fee`");
        if ($res2) {
            $r2 = mysqli_fetch_assoc($res2);
            $total_paid = (float)($r2['total_paid'] ?? 0);
        }

        return [
            'total_expected' => $total_expected,
            'total_paid' => $total_paid,
        ];
    }

    /**
     * Tổng chi phí phát sinh
     */
    public function getTotalExpenses(): float {
        global $con;
        $total = 0.0;
        $res = mysqli_query($con, "SELECT SUM(`amt`) AS total_expenses FROM `expenses`");
        if ($res) {
            $r = mysqli_fetch_assoc($res);
            $total = (float)($r['total_expenses'] ?? 0);
        }
        return $total;
    }

    /**
     * Lấy danh sách chi phí phát sinh gần nhất
     * Trả về mảng các rows: [ ['date'=>..., 'amt'=>..., 'particular'=>...], ... ]
     */
    public function getRecentExpenses(int $limit = 10): array {
        global $con;
        $out = [];
        $limit = max(1, (int)$limit);
        $sql = "SELECT `date`, `amt`, `particular` FROM `expenses` ORDER BY `id` DESC LIMIT {$limit}";
        $res = mysqli_query($con, $sql);
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) {
                $out[] = $r;
            }
        }
        return $out;
    }

    /**
     * Trả về counts chung từ AdminStatsRepository (nếu cần)
     */
    public function getAllCounts(): array {
        return $this->statsRepo->getAllCounts();
    }
}
?>