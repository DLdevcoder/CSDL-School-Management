<h3 class="text-center text-white bg-primary">Thêm học sinh</h3><hr>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Tên học sinh</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="studentName" value="<?php echo htmlspecialchars($_POST['studentName'] ?? ''); ?>" required />
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Địa chỉ</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>" />
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Lớp</label>
        <div class="col-sm-10">
            <select class="form-control" name="class" required>
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php if ((int)($_POST['class'] ?? 0) === $i) echo 'selected'; ?>>Lớp <?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Khóa học</label>
        <div class="col-sm-10">
            <select class="form-control" name="batch" required>
                <?php foreach ($options['courses'] as $c): ?>
                    <option value="<?php echo (int)$c['course_id']; ?>" <?php if (((int)($_POST['batch'] ?? 0)) === (int)$c['course_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($c['course_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- medium, gender, mobile, email, school, fee, password, subjects, competitives, dob, image -->
    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Bằng cấp</label>
        <div class="col-sm-10">
            <select class="form-control" name="medium" required>
                <option value="Marathi" <?php if(($_POST['medium'] ?? '') === 'Marathi') echo 'selected';?>>Marathi</option>
                <option value="SEMI" <?php if(($_POST['medium'] ?? '') === 'SEMI') echo 'selected';?>>SEMI</option>
                <option value="CBSE" <?php if(($_POST['medium'] ?? '') === 'CBSE') echo 'selected';?>>CBSE</option>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Giới tính</label>
        <div class="col-sm-10">
            <select class="form-control" name="gender" required>
                <option value="male" <?php if(($_POST['gender'] ?? '') === 'male') echo 'selected';?>>Nam</option>
                <option value="female" <?php if(($_POST['gender'] ?? '') === 'female') echo 'selected';?>>Nữ</option>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Điện thoại</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="mobile" value="<?php echo htmlspecialchars($_POST['mobile'] ?? ''); ?>" required />
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Email</label>
        <div class="col-sm-10">
            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required />
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Trường</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="school" value="<?php echo htmlspecialchars($_POST['school'] ?? ''); ?>" required />
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Học phí</label>
        <div class="col-sm-10">
            <input type="number" step="0.01" class="form-control" name="fee" value="<?php echo htmlspecialchars($_POST['fee'] ?? ''); ?>" required />
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Mật khẩu</label>
        <div class="col-sm-10">
            <input type="password" class="form-control" name="password" required />
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Môn học</label>
        <div class="col-sm-10">
            <?php foreach ($options['subjects'] as $s): ?>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" name="sub[]" value="<?php echo htmlspecialchars($s['subjectName']); ?>"
                        <?php if (!empty($_POST['sub']) && in_array($s['subjectName'], $_POST['sub'])) echo 'checked'; ?>/>
                        <?php echo htmlspecialchars($s['subjectName']); ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Kỳ thi</label>
        <div class="col-sm-10">
            <?php foreach ($options['competitives'] as $c): ?>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" name="com[]" value="<?php echo htmlspecialchars($c['examName']); ?>"
                        <?php if (!empty($_POST['com']) && in_array($c['examName'], $_POST['com'])) echo 'checked'; ?>/>
                        <?php echo htmlspecialchars($c['examName']); ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Ngày sinh</label>
        <div class="col-sm-10">
            <input type="date" class="form-control" name="date" value="<?php echo htmlspecialchars($_POST['date'] ?? ''); ?>" />
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Ảnh học sinh</label>
        <div class="col-sm-10">
            <input type="file" class="form-control-file btn btn-danger" name="u_image" />
        </div>
    </div>

    <div class="form-group row">
        <div class="offset-sm-2 col-sm-10">
            <button class="btn btn-outline-primary btn-block" name="submit" type="submit">Thêm học sinh</button>
        </div>
    </div>
</form>