<?php
require_once __DIR__ . '/../inc/db.php'; // hoặc đường dẫn tới file tạo $con

class AdminStatsRepository {
    private mysqli $con;

    // Danh sách bảng được phép truy vấn (whitelist)
    private array $allowed = [
        'gallery','student','review','courses','register',
        'fee','category','expenses','exam','msg','msgtoclasses'
    ];

    public function __construct() {
        global $con; // lấy từ file db.php
        $this->con = $con;
    }

    /**
     * Đếm số bản ghi trong một bảng (đã whitelist)
     */
    public function countTable(string $table): int {
        $table = preg_replace('/[^a-z0-9_]/i', '', $table);

        if (!in_array($table, $this->allowed, true)) {
            return 0; // nếu không nằm trong danh sách an toàn, bỏ qua
        }

        $query = "SELECT COUNT(*) AS c FROM `$table`";
        $result = mysqli_query($this->con, $query);

        if (!$result) {
            // có thể log lỗi ở đây nếu cần
            return 0;
        }

        $row = mysqli_fetch_assoc($result);
        return (int) ($row['c'] ?? 0);
    }

    /**
     * Lấy toàn bộ số lượng từ tất cả bảng
     */
    public function getAllCounts(): array {
        $counts = [];

        foreach ($this->allowed as $table) {
            $counts[$table] = $this->countTable($table);
        }

        return $counts;
    }
}
?>
