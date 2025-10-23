<div class="row">
    <div class="col-md-12">
        <h3 class="text-center text-white bg-primary p-2">Thêm khóa học mới</h3>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-danger">Tên khóa học</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Nhập tên" name="courseName" required
                           value="<?php echo htmlspecialchars($_POST['courseName'] ?? ''); ?>" />
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-danger">Lớp</label>
                <div class="col-sm-10">
                    <select class="form-control" name="class" required>
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <option value="<?php echo $i; ?>">Lớp <?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-danger">Thời gian</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Ví dụ: 3 tháng" name="duration" required
                           value="<?php echo htmlspecialchars($_POST['duration'] ?? ''); ?>" />
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-danger">Học phí</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" placeholder="Nhập học phí" name="fee" required
                           value="<?php echo htmlspecialchars($_POST['fee'] ?? '0'); ?>" />
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-danger">Bắt đầu từ</label>
                <div class="col-sm-10">
                    <input type="date" class="form-control" name="date" required
                           value="<?php echo htmlspecialchars($_POST['date'] ?? date('Y-m-d')); ?>" />
                </div>
            </div>

            <div class="form-group row">
                <div class="offset-sm-2 col-sm-10">
                    <button class="btn btn-primary btn-block" type="submit">Thêm khóa học</button>
                </div>
            </div>
        </form>
    </div>
</div>