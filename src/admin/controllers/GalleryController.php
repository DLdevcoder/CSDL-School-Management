<?php
require_once __DIR__ . '/../inc/auth.php';   
require_once __DIR__ . '/../inc/base.php'; 
require_once __DIR__ . '/../services/GalleryService.php';

class GalleryController {
    protected GalleryService $service;

    public function __construct() {
        $this->service = new GalleryService();
    }

    public function list() {
        requireAdmin();
        if (isset($_GET['del'])) {
            $id = (int)$_GET['del'];
            $result = $this->service->deleteGalleryItem($id);

            if ($result === true) {
                $_SESSION['flash_message'] = "Đã xóa ảnh thành công!";
            } else {
                $_SESSION['flash_message'] = "Lỗi: " . $result;
            }
            header('Location: ' . BASE_URL . '/src/admin/index.php?page=gallery&action=list');
            exit;
        }

        $galleryItems = $this->service->getAllGalleryItems();

        ob_start();
        include __DIR__ . '/../presentation/gallery/list.php';
        $content = ob_get_clean();
        include __DIR__ . '/../presentation/partials/layout.php';
    }

    public function create() {
        requireAdmin();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->service->createGalleryItem($_POST, $_FILES);
            if ($result === true) {
                $_SESSION['flash_message'] = "Thêm ảnh mới thành công!";
                header('Location: ' . BASE_URL . '/src/admin/index.php?page=gallery&action=list');
                exit;
            } else {
                $error = $result;
            }
        }

        ob_start();
        include __DIR__ . '/../presentation/gallery/create.php';
        $content = ob_get_clean();
        include __DIR__ . '/../presentation/partials/layout.php';
    }

    public function edit() {
        requireAdmin();
        $error = null;
        $id = (int)($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            header('Location: ' . BASE_URL . '/src/admin/index.php?page=gallery&action=list');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->service->updateGalleryItem($id, $_POST, $_FILES);
            if ($result === true) {
                $_SESSION['flash_message'] = "Cập nhật ảnh thành công!";
                header('Location: ' . BASE_URL . '/src/admin/index.php?page=gallery&action=list');
                exit;
            } else {
                $error = $result;
            }
        }

        $galleryItem = $this->service->getGalleryItemById($id);
        if (!$galleryItem) {
            $_SESSION['flash_message'] = "Lỗi: Không tìm thấy ảnh!";
            header('Location: ' . BASE_URL . '/src/admin/index.php?page=gallery&action=list');
            exit;
        }
        
        ob_start();
        include __DIR__ . '/../presentation/gallery/edit.php';
        $content = ob_get_clean();
        include __DIR__ . '/../presentation/partials/layout.php';
    }
}