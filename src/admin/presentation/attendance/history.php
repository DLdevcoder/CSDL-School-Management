<?php
$student = $data['student'];
$history = $data['history'];
?>

<h2 class="text-center text-white bg-primary p-2">
    Chi tiết điểm danh: <?php echo htmlspecialchars($student['name']); ?>
</h2>
<hr>
<a class="btn btn-info mb-3" href="index.php?page=student&action=view&id=<?php echo $student['id']; ?>">Quay lại chi tiết sinh viên</a>

<div class="row">
    <?php if (empty($history)): ?>
        <div class="col-12"><p class="text-muted">Chưa có dữ liệu điểm danh cho sinh viên này.</p></div>
    <?php else: ?>
        <?php foreach ($history as $record): ?>
            <div class="col-md-2 mb-3">
                <div class="card text-center">
                    <div class="card-header">
                        <?php echo date('d/m/Y', strtotime($record['date'])); ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title <?php echo ($record['attendance'] == "Present") ? "text-primary" : "text-danger"; ?>">
                            <?php echo ($record['attendance'] == "Present") ? "Có mặt" : "Vắng mặt"; ?>
                        </h5>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>