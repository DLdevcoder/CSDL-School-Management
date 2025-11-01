<?php
require_once __DIR__ . '/../repositories/GalleryRepository.php';
require_once __DIR__ . '/../validator/GalleryValidator.php';

class GalleryService {
    protected GalleryRepository $repo;

    public function __construct() {
        $this->repo = new GalleryRepository();
    }

    public function getAllGalleryItems(): array {
        return $this->repo->findAll();
    }

    public function deleteGalleryItem(int $id): bool|string {
        $error = GalleryValidator::validateId($id);
        if ($error) return $error;

        $item = $this->repo->findById($id);
        if (!$item) return "Không tìm thấy ảnh để xóa.";

        $imageFile = $item['gallery_image'];
        $deleted = $this->repo->delete($id);
        if (!$deleted) return "Lỗi khi xóa dữ liệu trong cơ sở dữ liệu.";

        $this->repo->deleteImageFile($imageFile);
        return true;
    }

    public function createGalleryItem(array $post, array $files): bool|string {
        $title = trim($post['imageTitle'] ?? '');
        $imageFile = $files['u_image'] ?? null;

        // ✅ Gọi validator
        $errors = [];
        if ($err = GalleryValidator::validateTitle($title)) $errors[] = $err;
        if ($err = GalleryValidator::validateImageFile($imageFile)) $errors[] = $err;

        if (!empty($errors)) {
            return implode(' ', $errors);
        }

        // ✅ Nếu hợp lệ thì lưu ảnh
        $imageName = $this->repo->saveImage($imageFile);
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
        $error = GalleryValidator::validateId($id);
        if ($error) return null;
        return $this->repo->findById($id);
    }

    public function updateGalleryItem(int $id, array $post, array $files): bool|string {
        $error = GalleryValidator::validateId($id);
        if ($error) return $error;

        $title = trim($post['imageTitle'] ?? '');
        if ($err = GalleryValidator::validateTitle($title)) return $err;

        $currentItem = $this->repo->findById($id);
        if (!$currentItem) return "Không tìm thấy ảnh để cập nhật.";

        $imageName = $currentItem['gallery_image'];

        // ✅ Nếu có upload ảnh mới
        if (!empty($files['u_image']['tmp_name'])) {
            if ($err = GalleryValidator::validateImageFile($files['u_image'])) return $err;

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
?>
