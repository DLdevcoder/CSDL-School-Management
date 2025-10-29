<h2 class="text-center text-white bg-primary p-2">Danh sách đăng ký</h2>
<hr>

<?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-info">
        <?php echo $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
    </div>
<?php endif; ?>

<table class="table table-bordered" id="registrationTable">
    <thead class="thead-dark">
        <tr> 
            <th>STT</th> 
            <th>Tên</th> 
            <th>Email</th> 
            <th>Điện thoại</th> 
            <th>Địa chỉ</th>
            <th>Lớp</th>
            <th>Ngày</th>
            <th>Xóa</th>
        </tr> 
    </thead>
    <tbody>
        <?php foreach ($registrations as $i => $reg): ?>
        <tr> 
            <td><?php echo $i + 1; ?></td>
            <td><?php echo htmlspecialchars(ucfirst($reg['regName'])); ?></td>
            <td><?php echo htmlspecialchars($reg['regEmail']); ?></td>
            <td><?php echo htmlspecialchars($reg['regMobile']); ?></td>
            <td><?php echo htmlspecialchars($reg['regAddress']); ?></td>
            <td><?php echo htmlspecialchars($reg['regQua']); ?></td>
            <td><?php echo htmlspecialchars($reg['date']); ?></td>
            <td>
                <a href="index.php?page=registration&action=list&del=<?php echo $reg['regid']; ?>" 
                   class="btn btn-danger" 
                   onclick="return confirm('Bạn có chắc chắn muốn xóa bản đăng ký này?');">
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
        $('#registrationTable').DataTable();
    });

    $("#btnExportExcel").click(function(){
        $("#registrationTable").table2excel({
            name: "Registration List",
            filename: "registrations.xls"
        });
    });
</script>