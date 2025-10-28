<div class="row">
    <div class="col-md-12">
        <h3 class="text-center text-white bg-primary p-2">Thêm ảnh vào thư viện</h3>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-danger">Tiêu đề</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Thêm tiêu đề" name="imageTitle" required
                           value="<?php echo htmlspecialchars($_POST['imageTitle'] ?? ''); ?>" />
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-danger">Ảnh</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control-file" name="u_image" required accept="image/*" />
                </div>
            </div>

            <div class="form-group row">
                <div class="offset-sm-2 col-sm-10">
                    <button class="btn btn-primary btn-block" name="submit" type="submit">Thêm ảnh</button>
                </div>
            </div>
        </form>
    </div>
</div>