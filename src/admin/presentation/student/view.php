<?php
$profile = $studentDetails['profile'];
$fees = $studentDetails['fees'];
$results = $studentDetails['results'];
$attendance = $studentDetails['attendance'];
?>
<h3 class="text-center text-white bg-primary p-2">Chi tiết học sinh: <?php echo htmlspecialchars($profile['name']); ?></h3>

<?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-info"><?php echo $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-4">
        <table class="table table-bordered">
            <tr><th class="bg-dark text-white">Địa chỉ</th><td><?php echo htmlspecialchars($profile['address']); ?></td></tr>
            <tr><th class="bg-dark text-white">Lớp</th><td><?php echo htmlspecialchars($profile['class']); ?></td></tr>
            <tr><th class="bg-dark text-white">Khóa học</th><td><?php echo htmlspecialchars($profile['course_name']); ?></td></tr>
            <tr><th class="bg-dark text-white">Điện thoại</th><td><?php echo htmlspecialchars($profile['mobile']); ?></td></tr>
            <tr><th class="bg-dark text-white">Email</th><td><?php echo htmlspecialchars($profile['email']); ?></td></tr>
        </table>
    </div>
    <div class="col-md-4">
        <table class="table table-bordered">
            <tr><th class="bg-dark text-white">Trường</th><td><?php echo htmlspecialchars($profile['school']); ?></td></tr>
            <tr><th class="bg-dark text-white">Môn học</th><td><?php echo htmlspecialchars($profile['subject']); ?></td></tr>
            <tr><th class="bg-dark text-white">Ngày sinh</th><td><?php echo htmlspecialchars($profile['dob']); ?></td></tr>
            <tr><th class="bg-dark text-white">Ngày đăng ký</th><td><?php echo htmlspecialchars($profile['date']); ?></td></tr>
            <tr><th class="bg-dark text-white">Giới tính</th><td><?php echo htmlspecialchars($profile['gender']); ?></td></tr>
        </table>
    </div>
    <div class="col-md-4 text-center">
        <img src="../images/student/<?php echo htmlspecialchars($profile['image']);?>" class="img-thumbnail" alt="Ảnh học sinh" style="max-height: 200px;">
    </div>
</div>
<hr>

<div class="row">
    <div class="col-md-12"><h3 class="text-center text-white bg-primary p-2">Chi tiết học phí</h3></div>
    <div class="col-md-4">
        <form action="" method="post">
            <div class="form-group"><label>Thêm lượng học phí</label><input type="number" class="form-control" name="feepaid" required /></div>
            <div class="form-group"><label>Số hóa đơn</label><input type="text" class="form-control" name="rNo" /></div>
            <button class="btn btn-primary" name="addFees">Nộp học phí</button>
        </form>
    </div>
    <div class="col-md-4">
        <h6>Lịch sử đóng tiền</h6>
        <table class="table table-bordered table-sm">
            <?php foreach ($fees['history'] as $fee): ?>
            <tr><th><?php echo htmlspecialchars($fee['date']);?></th><td><?php echo number_format($fee['fees']);?></td></tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div class="col-md-4">
        <table class="table table-bordered">
            <tr><th class="bg-dark text-white">Tổng học phí</th><td><?php echo number_format($profile['fee']); ?></td></tr>
            <tr><th class="bg-dark text-white">Đã nộp</th><td><?php echo number_format($fees['total_paid']); ?></td></tr>
            <tr><th class="bg-danger text-white">Còn lại</th><td><?php echo number_format($fees['remaining']); ?></td></tr>
        </table>
    </div>
</div>
<hr>

<div class="row">
    <div class="col-md-8">
        <h3 class="text-center text-white bg-primary p-2">Điểm số</h3>
        <table class="table table-bordered">
            <thead class="thead-dark"><tr><th>Ngày</th><th>Môn học</th><th>Điểm tổng</th><th>Điểm nhận được</th></tr></thead>
            <tbody>
                <?php foreach ($results as $result): ?>
                <tr>
                    <td><?php echo htmlspecialchars($result['date']); ?></td>
                    <td><?php echo htmlspecialchars($result['subject']); ?></td>
                    <td><?php echo htmlspecialchars($result['totalMarks']); ?></td>
                    <td><?php echo htmlspecialchars($result['obtainmark']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-4">
        <h3 class="text-center text-white bg-primary p-2">Điểm danh</h3>
        <button type="button" class="btn btn-info btn-block">Số ngày đi học <span class="badge badge-light"><?php echo $attendance['present']; ?></span></button><hr>
        <button type="button" class="btn btn-danger btn-block">Số ngày vắng học <span class="badge badge-light"><?php echo $attendance['absent']; ?></span></button>
    </div>
</div>