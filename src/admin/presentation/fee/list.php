<h2 class="text-center text-white bg-primary p-2">Chi tiết học phí</h2>

<?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-info">
        <?php echo $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
    </div>
<?php endif; ?>

<table class="table table-bordered" id="feeTable">
    <thead class="thead-dark">
        <tr> 
            <th>STT</th> 
            <th>Tên</th> 
            <th>Lớp</th> 
            <th>Khóa học</th> 
            <th>Số tiền</th>
            <th>Số hóa đơn</th>
            <th>Ngày</th>
            <th>Xóa</th>
        </tr> 
    </thead>
    <tbody>
        <?php foreach ($feeRecords as $i => $record): ?>
        <tr> 
            <td><?php echo $i + 1; ?></td>
            <td><?php echo htmlspecialchars(ucfirst($record['student_name'])); ?></td>
            <td>Lớp <?php echo htmlspecialchars($record['class']); ?></td>
            <td><?php echo htmlspecialchars($record['course_name']); ?></td>
            <td><?php echo number_format($record['fees']); ?></td>
            <td><?php echo htmlspecialchars($record['rNo']); ?></td>
            <td><?php echo htmlspecialchars($record['date']); ?></td>
            <td>
                <a href="index.php?page=fee&action=list&del=<?php echo $record['id']; ?>" 
                   class="btn btn-danger" 
                   onclick="return confirm('Bạn có chắc chắn muốn xóa bản ghi này?');">
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
        $('#feeTable').DataTable();
    });

    $("#btnExportExcel").click(function(){
        $("#feeTable").table2excel({
            name: "Fee Details",
            filename: "fee_details.xls"
        });
    });
</script>