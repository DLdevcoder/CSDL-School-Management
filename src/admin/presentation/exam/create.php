<h3 class="text-center text-white bg-primary p-2">Thêm kỳ thi mới</h3>
<hr>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<form action="" method="post">
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
        <label class="col-sm-2 col-form-label text-danger">Khóa học</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" placeholder="Nhập khóa học" name="batchName" required
                   value="<?php echo htmlspecialchars($_POST['batchName'] ?? ''); ?>" />
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Môn học</label>
        <div class="col-sm-10">
            <?php foreach ($formData['subjects'] as $subject): ?>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" name="subjects[]" 
                               value="<?php echo htmlspecialchars($subject['subjectName']); ?>">
                        <?php echo htmlspecialchars($subject['subjectName']); ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Điểm tổng</label>
        <div class="col-sm-10">
            <input type="number" class="form-control" placeholder="Nhập điểm" name="totalMark" required
                   value="<?php echo htmlspecialchars($_POST['totalMark'] ?? ''); ?>" />
        </div>
    </div>
    
    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Ngày kiểm tra</label>
        <div class="col-sm-10">
            <input type="date" class="form-control" name="date" required
                   value="<?php echo htmlspecialchars($_POST['date'] ?? date('Y-m-d')); ?>" />
        </div>
    </div>

    <div class="form-group row">
        <div class="offset-sm-2 col-sm-10">
            <button class="btn btn-primary btn-block" name="submit" type="submit">Thêm kỳ thi</button>
        </div>
    </div>
</form>