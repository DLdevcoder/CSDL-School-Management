<h3 class="text-center text-white bg-primary">Sửa học sinh</h3><hr>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Tên học sinh</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="studentName" value="<?php echo htmlspecialchars($student['name'] ?? ''); ?>" required />
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Địa chỉ</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($student['address'] ?? ''); ?>" />
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Lớp</label>
        <div class="col-sm-10">
            <select class="form-control" name="class" required>
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php if ((int)($student['class'] ?? 0) === $i) echo 'selected'; ?>>Lớp <?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Khóa học</label>
        <div class="col-sm-10">
            <select class="form-control" name="batch" required>
                <?php foreach ($options['courses'] as $c): ?>
                    <option value="<?php echo (int)$c['course_id']; ?>" <?php if ((int)($student['batch'] ?? 0) === (int)$c['course_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($c['course_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- other fields simplified: medium, gender, mobile, email, school, fee, password -->
    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Điện thoại</label>
        <div class="col-sm-10">
            <input class="form-control" name="mobile" value="<?php echo htmlspecialchars($student['mobile'] ?? ''); ?>" required>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Email</label>
        <div class="col-sm-10">
            <input class="form-control" name="email" value="<?php echo htmlspecialchars($student['email'] ?? ''); ?>" required>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Học phí</label>
        <div class="col-sm-10">
            <input type="number" step="0.01" class="form-control" name="fee" value="<?php echo htmlspecialchars($student['fee'] ?? ''); ?>" required />
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Mật khẩu (để trống để giữ nguyên)</label>
        <div class="col-sm-10">
            <input type="password" class="form-control" name="password" />
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Môn học</label>
        <div class="col-sm-10">
            <?php
            $subjectArray = array_filter(array_map('trim', explode(',', $student['subject'] ?? '')));
            foreach ($options['subjects'] as $s): ?>
                <label class="mr-2">
                    <input type="checkbox" name="sub[]" value="<?php echo htmlspecialchars($s['subjectName']); ?>"
                        <?php if (in_array($s['subjectName'], $subjectArray)) echo 'checked'; ?>> <?php echo htmlspecialchars($s['subjectName']); ?>
                </label>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Kỳ thi</label>
        <div class="col-sm-10">
            <?php
            $compArray = array_filter(array_map('trim', explode(',', $student['cexam'] ?? '')));
            foreach ($options['competitives'] as $c): ?>
                <label class="mr-2">
                    <input type="checkbox" name="com[]" value="<?php echo htmlspecialchars($c['examName']); ?>"
                        <?php if (in_array($c['examName'], $compArray)) echo 'checked'; ?>> <?php echo htmlspecialchars($c['examName']); ?>
                </label>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Ngày sinh</label>
        <div class="col-sm-10">
            <input type="date" name="date" class="form-control" value="<?php echo htmlspecialchars($student['dob'] ?? ''); ?>">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Ảnh (để trống nếu muốn giữ ảnh cũ)</label>
        <div class="col-sm-10">
            <input type="file" name="u_image" class="form-control-file" />
            <?php if (!empty($student['image'])): ?>
                <div class="mt-2"><img src="<?php echo htmlspecialchars(BASE_URL . '/images/student/' . $student['image']); ?>" style="max-width:120px"></div>
            <?php endif; ?>
        </div>
    </div>

    <div class="form-group row">
        <div class="offset-sm-2 col-sm-10">
            <button class="btn btn-primary" type="submit">Lưu</button>
            <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/src/admin/index.php?page=student&action=list">Hủy</a>
        </div>
    </div>
</form>