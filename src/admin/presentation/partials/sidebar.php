<?php
if (!defined('BASE_URL')) {
        $script = str_replace('\\','/', $_SERVER['SCRIPT_NAME']);
    $parts = explode('/src/', $script, 2);
    $base = isset($parts[0]) ? rtrim($parts[0], '/') : '';
    if ($base === '') $base = ''; // fallback to root
    define('BASE_URL', $base);
}
require_once __DIR__ . '/../../repositories/AdminStatsRepository.php';
$statsRepo = new AdminStatsRepository();
$counts = $statsRepo->getAllCounts();
?>

<div class="list-group">
    <a href="<?php echo BASE_URL; ?>/src/admin/index.php"
       class="list-group-item list-group-item-action active">
        <i class="fa fa-tachometer"></i> Dashboard
    </a>

    <?php
    $menuItems = [
        ['page'=>'gallery',      'table'=>'gallery',      'icon'=>'fa-camera',      'label'=>'Thư viện ảnh'],
        ['page'=>'student',      'table'=>'student',      'icon'=>'fa-user',        'label'=>'Học sinh'],
        ['page'=>'review',       'table'=>'review',       'icon'=>'fa-star',        'label'=>'Nhận xét'],
        ['page'=>'courses',      'table'=>'courses',      'icon'=>'fa-book',        'label'=>'Khóa học'],
        ['page'=>'register',     'table'=>'register',     'icon'=>'fa-lightbulb-o', 'label'=>'Đăng ký'],
        ['page'=>'fee',          'table'=>'fee',          'icon'=>'fa-money',       'label'=>'Học phí'],
        ['page'=>'category',     'table'=>'category',     'icon'=>'fa-sort',        'label'=>'Danh mục'],
        ['page'=>'expenses',     'table'=>'expenses',     'icon'=>'fa-credit-card', 'label'=>'Chi phí'],
        ['page'=>'exam',         'table'=>'exam',         'icon'=>'fa-question',    'label'=>'Kỳ thi'],
        ['page'=>'msg',          'table'=>'msg',          'icon'=>'fa-envelope',    'label'=>'Thông báo'],
        ['page'=>'msgtoclasses', 'table'=>'msgtoclasses', 'icon'=>'fa-comments',    'label'=>'Phản hồi'],
    ];

    foreach ($menuItems as $item):
        $count = $counts[$item['table']] ?? 0;
    ?>
        <a href="<?php echo BASE_URL; ?>/src/admin/index.php?page=<?php echo $item['page']; ?>&action=list"
           class="list-group-item list-group-item-action">
            <i class="fa <?php echo $item['icon']; ?>"></i>
            <?php echo htmlspecialchars($item['label']); ?>
            <span class="badge badge-pill badge-light float-right text-danger">
                <?php echo $count; ?>
            </span>
        </a>
    <?php endforeach; ?>
</div>