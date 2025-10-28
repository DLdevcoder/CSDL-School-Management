<h2 class="text-center text-white bg-primary p-2">Danh sách kỳ thi</h2>
<div class="text-right">
    <a href="index.php?page=exam&action=create" class="btn btn-outline-primary">Thêm kỳ thi</a>
    <hr>
</div>

<?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-info">
        <?php echo $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
    </div>
<?php endif; ?>

<table class="table table-bordered" id="examTable">
    <thead class="thead-dark">
        <tr>
            <th>STT</th>
            <th>Tên khóa học</th>
            <th>Ngày</th>
            <th>Môn học</th>
            <th>Điểm tổng</th>
            <th>Xem</th>
            <th>Sửa</th>
            <th>Xóa</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($exams as $i => $exam): ?>
        <tr>
            <td><?php echo $i + 1; ?></td>
            <td><?php echo htmlspecialchars(ucfirst($exam['batchName'])); ?></td>
            <td><?php echo htmlspecialchars($exam['date']); ?></td>
            <td><?php echo htmlspecialchars($exam['subject']); ?></td>
            <td><?php echo htmlspecialchars($exam['totalMark']); ?></td>
            <td>
                <a class="btn btn-primary" href="index.php?page=exam&action=view&id=<?php echo $exam['id']; ?>">
                    <i class="fa fa-eye"></i>
                </a>
            </td>
            <td>
                <a class="btn btn-warning" href="index.php?page=exam&action=edit&id=<?php echo $exam['id']; ?>">
                    <i class="fa fa-pencil-square-o"></i>
                </a>
            </td>
            <td>
                <a class="btn btn-danger" href="index.php?page=exam&action=list&del=<?php echo $exam['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa kỳ thi này?');">
                    <i class="fa fa-trash-o"></i>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<button class="btn btn-danger offset-md-4 mt-3" id="btnExportExcel" type="button">Xuất ra Excel</button>

<script>
    $(document).ready(function() {
        $('#examTable').DataTable();
    });

    $("#btnExportExcel").click(function(){
        $("#examTable").table2excel({
            name: "Exam List",
            filename: "exams.xls"
        });
    });
</script>