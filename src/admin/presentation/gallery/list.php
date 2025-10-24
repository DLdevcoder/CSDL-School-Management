<div class="row">
    <div class="col-md-12">
        <h2 class="text-center text-white bg-primary p-2">Thư viện ảnh</h2>
        <div class="text-right">
            <a href="index.php?page=gallery&action=create" class="btn btn-outline-primary">Thêm ảnh</a>
            <hr>
        </div>
        
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-info">
                <?php echo $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>

        <table class="table table-bordered" id="table2excel">
            <thead class="thead-dark">
                <tr> 
                    <th>STT</th> 
                    <th>Tiêu đề</th> 
                    <th>Ảnh</th> 
                    <th>Sửa</th> 
                    <th>Xóa</th> 
                </tr> 
            </thead>
            <tbody>
                <?php foreach($galleryItems as $i => $item): ?>
                <tr> 
                    <td><?php echo $i + 1; ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($item['gallery_title'])); ?></td>
                    <td>
                        <img class="img-fluid" src="../images/gallery/<?php echo htmlspecialchars($item['gallery_image']); ?>" width="100px;" />
                    </td>
                    <td>
                        <a class="btn btn-warning" href="index.php?page=gallery&action=edit&id=<?php echo $item['gallery_id']; ?>">
                            <i class="fa fa-pencil-square-o"></i>
                        </a>
                    </td>
                    <td>
                        <a class="btn btn-danger" href="index.php?page=gallery&action=list&del=<?php echo $item['gallery_id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa ảnh này?');">
                            <i class="fa fa-trash-o"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button id="btn" class="btn btn-danger offset-md-4">Xuất ra Excel</button>
    </div>
</div>

<script>
$(document).ready(function(){
    const table = $('#table2excel').DataTable();
    $('#btn').click(function(){
        table.destroy();
        $("#table2excel").table2excel({
            exclude: ".noExl", 
            name: "Gallery",
            filename: "gallery.xls", 
            preserveColors: false
        });
        $('#table2excel').DataTable();
    });
});
</script>