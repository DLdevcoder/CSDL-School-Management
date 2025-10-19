<?php
require_once __DIR__ . '/inc/top.php';
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mt-2">
            <?php include __DIR__ . '/inc/navbar.php'; ?>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-9">
            <h3 class="text-center bg-primary text-white">Results</h3>
            <div class="list-group">
                <a href = "#" class="list-group-item list-group-item-action">TOEIC Exam Result</a>
                <a href = "#" class="list-group-item list-group-item-action">Scholarship Exam</a>
                <a href = "#" class="list-group-item list-group-item-action">Top Coder 2022</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h4 class="card-title text-center">Parents's Review</h4>
                </div>
            </div>
            <img src="../../images/parents-review.png" alt="review" class="img-fluid">
        </div>
    </div>

    <div class="container-fluid">
        <div class="row bg-dark mt-2 p-3">
            <?php include __DIR__ . '/inc/footer.php'; ?>
        </div>
    </div>
</div>

</body>

</html>