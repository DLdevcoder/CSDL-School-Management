<h3 class="text-center text-white bg-primary p-2">Thêm chi phí mới</h3>
<hr>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<form action="" method="post">
    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Chi tiết</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" placeholder="Nhập chi tiết" name="particular" required
                   value="<?php echo htmlspecialchars($_POST['particular'] ?? ''); ?>" />
        </div>
    </div>
    
    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Số tiền</label>
        <div class="col-sm-10">
            <input type="number" placeholder="Nhập số tiền" class="form-control" name="amt" required
                   value="<?php echo htmlspecialchars($_POST['amt'] ?? ''); ?>" />
        </div>
    </div>
    
    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-danger">Ngày</label>
        <div class="col-sm-10">
            <input type="date" class="form-control" name="date" required
                   value="<?php echo htmlspecialchars($_POST['date'] ?? date('Y-m-d')); ?>" />
        </div>
    </div>

    <div class="form-group row">
        <div class="offset-sm-2 col-sm-10">
            <button class="btn btn-primary btn-block" name="submit" type="submit">Thêm chi phí</button>
        </div>
    </div>
</form>