<h2 class="text-center text-white bg-primary p-2">Chi tiết chi phí</h2>
<div class="text-right">
    <a href="index.php?page=expenses&action=create" class="btn btn-outline-primary">Thêm chi phí</a>
    <hr>
</div>

<?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-info">
        <?php echo $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
    </div>
<?php endif; ?>

<table class="table table-bordered" id="expenseTable">
    <thead class="thead-dark">
        <tr> 
            <th>STT</th> 
            <th>Chi tiết</th> 
            <th>Ngày</th> 
            <th>Số tiền</th>
            <th>Xóa</th>
        </tr> 
    </thead>
    <tbody>
        <?php foreach ($expenses as $i => $expense): ?>
        <tr> 
            <td><?php echo $i + 1; ?></td>
            <td><?php echo htmlspecialchars($expense['particular']); ?></td>
            <td><?php echo htmlspecialchars($expense['date']); ?></td>
            <td><?php echo number_format($expense['amt']); ?></td>
            <td>
                <a href="index.php?page=expense&action=list&del=<?php echo $expense['id']; ?>" 
                   class="btn btn-danger" 
                   onclick="return confirm('Bạn có chắc chắn muốn xóa khoản chi này?');">
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
        $('#expenseTable').DataTable();
    });

    $("#btnExportExcel").click(function(){
        $("#expenseTable").table2excel({
            name: "Expense Details",
            filename: "expenses.xls"
        });
    });
</script>