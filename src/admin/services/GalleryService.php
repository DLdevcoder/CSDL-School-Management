<?php
require_once __DIR__ . '/../repositories/GalleryRepository.php';

class GalleryService {
    protected GalleryRepository $repo;

    public function __construct() {
        $this->repo = new GalleryRepository();
    }

    public function getAllGalleryItems(): array {
        return $this->repo->findAll();
    }

    public function deleteGalleryItem(int $id) {
        if ($id <= 0) {
            return "ID không hợp lệ.";
        }
        $item = $this->repo->findById($id);
        if (!$item) {
            return "Không tìm thấy ảnh để xóa.";
        }
        $imageFile = $item['gallery_image'];
        $deleted = $this->repo->delete($id);
        if (!$deleted) {
            return "Lỗi khi xóa dữ liệu trong cơ sở dữ liệu.";
        }
        $this->repo->deleteImageFile($imageFile);

        return true;
    }
}