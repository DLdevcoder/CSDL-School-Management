<?php
// Layout chung: phải được include bởi controller sau khi $content đã được chuẩn bị.
// Top sẽ định nghĩa BASE_URL và load CSS/JS
require_once __DIR__ . '/top.php';
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12 mt-2">
      <?php include __DIR__ . '/navbar.php'; ?>
    </div>
  </div>

  <div class="row mt-1">
    <div class="col-md-3">
      <?php include __DIR__ . '/sidebar.php'; ?>
    </div>

    <div class="col-md-9">
      <?php echo $content ?? ''; ?>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row bg-dark mt-2 p-3">
      <?php include __DIR__ . '/footer.php'; ?>
    </div>
  </div>
</div>

</body>
</html>