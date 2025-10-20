<?php
// presentation only: assumes $error (string|null) may be set by controller
?>
<div class="row">
    <div class="col-md-12">
        <h3 class="text-center text-white bg-primary">Thêm khóa học</h3><hr>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-danger">Tên khóa học</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="courseName" value="<?php echo htmlspecialchars($_POST['courseName'] ?? ''); ?>" required />
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-danger">Lớp</label>
                <div class="col-sm-10">
                    <select class="form-control" name="class" required>
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php if (isset($_POST['class']) && (int)$_POST['class'] === $i) echo 'selected'; ?>>Lớp <?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-danger">Thời gian</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="duration" value="<?php echo htmlspecialchars($_POST['duration'] ?? ''); ?>" required />
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-danger">Học phí</label>
                <div class="col-sm-10">
                    <input type="number" step="0.01" class="form-control" name="fee" value="<?php echo htmlspecialchars($_POST['fee'] ?? ''); ?>" required />
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-danger">Bắt đầu từ</label>
                <div class="col-sm-10">
                    <input type="date" class="form-control" name="date" value="<?php echo htmlspecialchars($_POST['date'] ?? ''); ?>" required />
                </div>
            </div>

            <div class="form-group row">
                <div class="offset-sm-2 col-sm-10">
                    <button class="btn btn-outline-primary btn-block" name="submit" type="submit">Thêm khóa học</button>
                </div>
            </div>
        </form>
    </div>
</div>