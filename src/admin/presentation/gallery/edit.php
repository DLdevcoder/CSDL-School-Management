<div class="row">
    <div class="col-md-12">
        <h3 class="text-center text-white bg-primary p-2">Sửa ảnh trong thư viện</h3>
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
                    <input type="text" class="form-control" name="imageTitle" required
                           value="<?php echo htmlspecialchars($galleryItem['gallery_title'] ?? ''); ?>" />
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Ảnh hiện tại</label>
                <div class="col-sm-10">
                    <img src="../images/gallery/<?php echo htmlspecialchars($galleryItem['gallery_image']); ?>" width="150px" class="img-thumbnail" />
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-danger">Chọn ảnh mới (nếu muốn thay đổi)</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control-file" name="u_image" accept="image/*" />
                </div>
            </div>

            <div class="form-group row">
                <div class="offset-sm-2 col-sm-10">
                    <button class="btn btn-primary btn-block" type="submit">Cập nhật ảnh</button>
                </div>
            </div>
        </form>
    </div>
</div>