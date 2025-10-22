<div class="container mt-3">
  <div class="d-flex justify-content-between mb-2">
    <h3>Danh sách học sinh</h3>
    <a class="btn btn-primary" href="<?php echo BASE_URL; ?>/src/admin/index.php?page=student&action=create">Thêm mới</a>
  </div>

  <table class="table table-striped">
    <thead><tr><th>#</th><th>Tên</th><th>Hành động</th></tr></thead>
    <tbody>
      <?php foreach ($students as $i => $s): ?>
        <tr>
          <td><?php echo $i+1; ?></td>
          <td><?php echo htmlspecialchars($s['name']); ?></td>
          <td>
            <a class="btn btn-sm btn-secondary" href="<?php echo BASE_URL; ?>/src/admin/index.php?page=student&action=edit&id=<?php echo $s['id']; ?>">Sửa</a>
            <a class="btn btn-sm btn-danger" href="<?php echo BASE_URL; ?>/src/admin/index.php?page=student&action=delete&id=<?php echo $s['id']; ?>" onclick="return confirm('Xóa?')">Xóa</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>