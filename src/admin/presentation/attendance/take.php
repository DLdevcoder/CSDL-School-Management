<h2 class="text-center text-white bg-primary p-2">
    Điểm danh học sinh
    <?php if (!empty($students)) echo '- ' . htmlspecialchars($students[0]['course_name']); ?>
</h2>

<?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-info">
        <?php echo $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
    </div>
<?php endif; ?>

<form method="post" action="">
    <div class="row mb-3">
        <div class="col-md-4">
            <select class="form-control" name="bulk-options" required>
                <option value="">Chọn hành động...</option>
                <option value="present">Có mặt</option>
                <option value="absent">Vắng mặt</option>
            </select>
        </div>
        <div class="col-md-8">
            <input type="submit" class="btn btn-warning" value="Áp dụng" onclick="return confirm('Bạn có chắc chắn muốn áp dụng hành động này?')" />
        </div>
    </div>

    <table class="table table-bordered" id="attendanceTable">
        <thead class="thead-dark">
            <tr> 
                <th><input type="checkbox" id="selectallboxes"/></th>
                <th>STT</th> 
                <th>Tên</th> 
                <th>Trường</th> 
                <th>Giới tính</th> 
                <th>Lớp</th> 
                <th>Ảnh</th> 
            </tr> 
        </thead>
        <tbody>
            <?php foreach($students as $i => $student): ?>
            <tr> 
                <td><input type="checkbox" class="checkboxes" name="checkboxes[]" value="<?php echo $student['id'];?>"/></td>
                <td><?php echo $i + 1; ?></td>
                <td><?php echo htmlspecialchars(ucfirst($student['name'])); ?></td>
                <td><?php echo htmlspecialchars($student['school']); ?></td>
                <td><?php echo htmlspecialchars($student['gender']); ?></td>
                <td><?php echo htmlspecialchars($student['class']); ?></td>
                <td>
                    <img class="img-fluid" src="../images/student/<?php echo htmlspecialchars($student['image']);?>" width="100px;" />
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</form>

<button class="btn btn-danger offset-md-4 mt-3" id="btnExportExcel" type="button">Xuất ra Excel</button>

<script>
    $(document).ready(function() {
        $('#attendanceTable').DataTable();

        $('#selectallboxes').click(function(event){
            if(this.checked){
                $('.checkboxes').each(function(){
                    this.checked = true;
                });
            } else {
                $('.checkboxes').each(function(){
                    this.checked = false;
                });
            }
        });

        $("#btnExportExcel").click(function(){
            $("#attendanceTable").table2excel({
                name: "Attendance Sheet",
                filename: "attendance.xls"
            });
        });
    });
</script>