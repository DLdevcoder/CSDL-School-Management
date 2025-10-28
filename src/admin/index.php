<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php
ob_start();
session_start();

// If session variable is not found
if (!isset($_SESSION['user_name'])) {
    // Redirect user to login page
    header('Location: login.php');
    exit; // dừng kịch bản sau redirect
}
$page = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? 'list';

if ($page === 'student') {
    require_once __DIR__ . '/controllers/StudentController.php';
    $ctrl = new StudentController();
    if ($action === 'list') $ctrl->list();
    elseif ($action === 'create' || $action === 'add') $ctrl->create();
    elseif ($action === 'edit') $ctrl->edit();
    elseif ($action === 'delete') $ctrl->delete();
    exit;
}

if ($page === 'course') {
    require_once __DIR__ . '/controllers/CourseController.php';
    $ctrl = new CourseController();
    switch ($action) {
        case 'create':
            $ctrl->create();
            break;
        case 'edit':
            $ctrl->edit();
            break;
        case 'list':
        default:
            $ctrl->list();
            break;
    }
    exit;
}

if ($page === 'gallery') {
    require_once __DIR__ . '/controllers/GalleryController.php';
    $ctrl = new GalleryController();

    switch ($action) {
        case 'create':
            $ctrl->create();
            break;
        case 'edit':
            $ctrl->edit();
            break;
        case 'list':
        default:
            $ctrl->list();
            break;
    }
    exit;
}

require_once('presentation/partials/top.php');
require_once('inc/db.php');

// thêm service
require_once __DIR__ . '/services/DashboardService.php';
$dashboardService = new DashboardService();

// Lấy dữ liệu từ service (thay vì query trực tiếp trong view)
$studentsPerClass = $dashboardService->getStudentsPerClass(10);
$feesSummary = $dashboardService->getTotalFees();
$totalExpenses = $dashboardService->getTotalExpenses();
$recentExpenses = $dashboardService->getRecentExpenses(10);
// ...existing code...
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mt-2">
            <?php include('presentation/partials/navbar.php'); ?>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-md-3">
            <?php include('presentation/partials/sidebar.php'); ?>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <img src="../images/logo.png" width="50" alt="logo" class="img-fluid"><hr>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h3 class="text-center text-white bg-primary">Thông tin về trường học</h3>
                </div>

                <div class="col-md-3">
                    <div class="card text-primary border-primary">
                        <div class="card-header bg-primary text-white">Sinh viên</div>
                        <div class="card-body">
                            <table class="table table-bordered table-condensed">
                                <tbody>
                                    <?php foreach ($studentsPerClass as $class => $count): ?>
                                    <tr>
                                        <th class="bg-dark text-white">Lớp <?php echo $class; ?></th>
                                        <th><?php echo $count; ?></th>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-primary border-warning">
                        <div class="card-header bg-warning text-white">Tổng phí thu được</div>
                        <div class="card-body">
                            <table class="table table-bordered table-condensed">
                                <tbody>
                                    <tr>
                                        <th class="bg-dark text-white">Tổng học phí</th>
                                        <th><?php echo $feesSummary['total_expected']; ?></th>
                                    </tr>
                                    <tr>
                                        <th class="bg-dark text-white">Học phí thu được</th>
                                        <th><?php echo $feesSummary['total_paid']; ?></th>
                                    </tr>
                                    <tr>
                                        <th class="bg-danger text-white">Học phí còn lại</th>
                                        <th><?php echo $feesSummary['total_expected'] - $feesSummary['total_paid']; ?></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card text-primary border-warning">
                        <div class="card-header bg-warning text-white">Số dư tiền mặt</div>
                        <div class="card-body">
                            <table class="table table-bordered table-condensed">
                                <tbody>
                                    <tr>
                                        <th class="bg-dark text-white">Học phí thu được</th>
                                        <th><?php echo $feesSummary['total_paid']; ?></th>
                                    </tr>
                                    <tr>
                                        <th class="bg-dark text-white">Tổng chi phí phát sinh</th>
                                        <th><?php echo $totalExpenses; ?></th>
                                    </tr>
                                    <tr>
                                        <th class="bg-danger text-white">Số dư còn lại</th>
                                        <th><?php echo $feesSummary['total_paid'] - $totalExpenses; ?></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <div class="col-md-5">
                    <div class="card text-primary border-danger">
                        <div class="card-header bg-danger text-white">Chi phí phát sinh <small>(Hiển thị 10 chi phí gần nhất)</small></div>
                        <div class="card-body">
                            <table class="table table-bordered table-condensed">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>STT</th>
                                        <th>Ngày</th>
                                        <th>Số tiền</th>
                                        <th>Ghi chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $ia = 0; foreach ($recentExpenses as $rowexpenses): $ia++; ?>
                                    <tr>
                                        <th><?php echo $ia; ?></th>
                                        <th><?php echo htmlspecialchars($rowexpenses['date']); ?></th>
                                        <th><?php echo htmlspecialchars($rowexpenses['amt']); ?></th>
                                        <th><?php echo htmlspecialchars($rowexpenses['particular']); ?></th>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="container-fluid">
        <div class="row bg-dark mt-2 p-3">
            <?php
            include __DIR__ . '/presentation/partials/footer.php';
            ?>
        </div>
    </div>
</div>

</body>

</html>