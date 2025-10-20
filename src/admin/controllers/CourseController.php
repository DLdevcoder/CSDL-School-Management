<?php
ob_start();
session_start();

if (!isset($_SESSION['user_name'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../inc/top.php';
require_once __DIR__ . '/../services/CourseService.php';

// Service layer
$service = new CourseService();

// Biến dùng chung
$error = null;
$success = null;
$action = $_GET['action'] ?? 'list';

switch ($action) {

    case 'add':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ok = $service->createCourse($_POST);
            if ($ok === true) {
                header('Location: course.php?action=list');
                exit;
            } else {
                $error = $ok ?: 'Không thể thêm khóa học.';
            }
        }
        $view = __DIR__ . '/../presentation/course/create.php';
        break;

    case 'delete':
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $ok = $service->deleteCourse($id);
            if ($ok) {
                header('Location: course.php?action=list');
                exit;
            } else {
                $error = 'Không thể xóa khóa học.';
            }
        }
        $courses = $service->getAllCourses();
        $view = __DIR__ . '/../presentation/course/list.php';
        break;

    case 'edit':
        // Sau này sẽ thêm form chỉnh sửa
        $error = 'Chức năng chỉnh sửa đang được phát triển.';
        $courses = $service->getAllCourses();
        $view = __DIR__ . '/../presentation/course/list.php';
        break;

    case 'list':
    default:
        $courses = $service->getAllCourses();
        $view = __DIR__ . '/../presentation/course/list.php';
        break;
}

?>
<div class="container-fluid">
    <div class="row mt-2">
        <div class="col-md-12">
            <?php include __DIR__ . '/../presentation/partials/navbar.php'; ?>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-md-3">
            <?php include __DIR__ . '/../presentation/partials/sidebar.php'; ?>
        </div>
        <div class="col-md-9">
            <div class="col-md-12 mb-2">
                <img src="<?php echo BASE_URL; ?>/images/logo.jpg" alt="logo" class="img-fluid"><hr>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php elseif ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php include $view; ?>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row bg-dark mt-2 p-3">
            <?php include __DIR__ . '/../presentation/partials/footer.php'; ?>
        </div>
    </div>
</div>

</body>
</html>
