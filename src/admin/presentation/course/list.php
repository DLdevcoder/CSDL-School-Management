<h2 class="text-center text-white bg-primary">Hiển thị khóa học</h2>
<div class="mb-2 text-right">
    <a href="<?php echo BASE_URL; ?>/src/admin/index.php?page=course&action=create" class="btn btn-outline-primary">Thêm khóa học</a>
</div>

<table class="table table-border" id="table2excel">
    <thead class="thead-dark">
        <tr>
            <th>STT</th>
            <th>Tên khóa học</th>
            <th>Lớp</th>
            <th>Thời gian</th>
            <th>Học phí</th>
            <th>Số lượng học sinh</th>
            <th>Bắt đầu từ</th>
            <th><i class="fa fa-eye"></i></th>
            <th><i class="fa fa-pencil-square-o"></i></th>
            <th><i class="fa fa-trash-o"></i></th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 0; foreach ($courses as $course): $i++; ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo htmlspecialchars(ucfirst($course['course_name'])); ?></td>
            <td><?php echo htmlspecialchars($course['class']); ?></td>
            <td><?php echo htmlspecialchars($course['course_duration']); ?></td>
            <td><?php echo htmlspecialchars($course['course_fee']); ?></td>
            <td><?php echo (int)$course['student_count']; ?></td>
            <td><?php echo htmlspecialchars($course['course_start']); ?></td>
            <td>
                <a class="btn btn-primary" href="<?php echo BASE_URL; ?>/src/admin/index.php?page=course&action=view&id=<?php echo (int)$course['course_id']; ?>"><i class="fa fa-eye"></i></a>
            </td>
            <td>
                <a class="btn btn-warning" href="<?php echo BASE_URL; ?>/src/admin/index.php?page=course&action=edit&id=<?php echo (int)$course['course_id']; ?>"><i class="fa fa-pencil-square-o"></i></a>
            </td>
            <td>
                <a class="btn btn-danger" href="<?php echo BASE_URL; ?>/src/admin/index.php?page=course&action=list&del=<?php echo (int)$course['course_id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?')"><i class="fa fa-trash-o"></i></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<button class="btn btn-danger offset-md-4" id="btn" type="button">Xuất ra Excel</button>

<script>
$(document).ready(function(){
    $('#table2excel').DataTable();
});

$("#btn").click(function(){
    $("#table2excel").table2excel({
        name:"Worksheet name",
        filename: "courses.xls"
    });
});
</script>