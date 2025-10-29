<h2 class="text-center text-white bg-primary p-2">Danh sách học sinh</h2>
<div class="text-right">
    <a href="index.php?page=student&action=create" class="btn btn-outline-primary">Thêm học sinh</a>
    <hr>
</div>

<?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-info">
        <?php echo $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
    </div>
<?php endif; ?>

<table class="table table-bordered" id="studentTable">
    <thead class="thead-dark">
        <tr> 
            <th>STT</th> 
            <th>Tên</th> 
            <th>Lớp</th> 
            <th>Khóa học</th> 
            <th>Ảnh</th> 
            <th>Xem</th> 
            <th>Sửa</th> 
            <th>Xóa</th> 
        </tr> 
    </thead>
    <tbody>
        <?php foreach ($students as $i => $student): ?>
        <tr> 
            <td><?php echo $i + 1; ?></td>
            <td><?php echo htmlspecialchars(ucfirst($student['name'])); ?></td>
            <td><?php echo htmlspecialchars($student['class']); ?></td>
            <td><?php echo htmlspecialchars($student['course_name']); ?></td>
            <td>
                <img class="img-fluid" src="../images/student/<?php echo htmlspecialchars($student['image']); ?>" width="100px;" />
            </td>
            <td>
                <a class="btn btn-primary" href="index.php?page=student&action=view&id=<?php echo $student['id']; ?>">
                    <i class="fa fa-eye"></i>
                </a>
            </td>
            <td>
                <a class="btn btn-warning" href="index.php?page=student&action=edit&id=<?php echo $student['id']; ?>">
                    <i class="fa fa-pencil-square-o"></i>
                </a>
            </td>
            <td>
                <a class="btn btn-danger" href="index.php?page=student&action=list&del=<?php echo $student['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên này?');">
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
        $('#studentTable').DataTable();
    });

    $("#btnExportExcel").click(function(){
        $("#studentTable").table2excel({
            name: "Student List",
            filename: "students.xls"
        });
    });
</script>