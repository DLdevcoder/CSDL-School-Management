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

    public function createGalleryItem(array $post, array $files) {
        $title = trim($post['imageTitle'] ?? '');
        if ($title === '') {
            return "Tiêu đề không được để trống.";
        }
        if (empty($files['u_image']['name'])) {
            return "Bạn phải chọn một file ảnh.";
        }
        $imageName = $this->repo->saveImage($files['u_image']);
        if ($imageName === false) {
            return "Không thể lưu file ảnh. Vui lòng thử lại.";
        }
        $data = [
            'title' => $title,
            'image' => $imageName
        ];

        $ok = $this->repo->insert($data);
        if (!$ok) {
            $this->repo->deleteImageFile($imageName);
            return "Lỗi khi lưu thông tin vào cơ sở dữ liệu.";
        }

        return true;
    }

    public function getGalleryItemById(int $id): ?array {
        return $this->repo->findById($id);
    }

    public function updateGalleryItem(int $id, array $post, array $files) {
        $title = trim($post['imageTitle'] ?? '');
        if ($title === '') {
            return "Tiêu đề không được để trống.";
        }
        $currentItem = $this->repo->findById($id);
        if (!$currentItem) {
            return "Không tìm thấy ảnh để cập nhật.";
        }
        $imageName = $currentItem['gallery_image'];

        if (!empty($files['u_image']['tmp_name'])) {
            $newImageName = $this->repo->saveImage($files['u_image']);
            if ($newImageName) {
                $this->repo->deleteImageFile($imageName);
                $imageName = $newImageName; 
            } else {
                return "Lỗi khi upload ảnh mới.";
            }
        }
        $data = [
            'title' => $title,
            'image' => $imageName
        ];

        $ok = $this->repo->update($id, $data);
        return $ok ? true : "Lỗi khi cập nhật cơ sở dữ liệu.";
    }
}