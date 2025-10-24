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
}