<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

$rooms  = $conn->query("SELECT RoomId, RoomNumber FROM room ORDER BY RoomNumber");
$staffs = $conn->query("SELECT StaffId, StaffName FROM staff ORDER BY StaffName");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Gửi báo cáo sự cố</title>
    <link rel="stylesheet" href="../incident.css">
</head>
<body>
<div class="incident-container">

    <h2>Gửi báo cáo sự cố</h2>

    <?php if (isset($_GET['ok'])): ?>
        <div style="background:#d1fae5;color:#065f46;padding:10px 16px;border-radius:8px;margin-bottom:16px;font-size:14px;">
            Báo cáo đã được gửi thành công!
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div style="background:#fee2e2;color:#991b1b;padding:10px 16px;border-radius:8px;margin-bottom:16px;font-size:14px;">
            Lỗi: <?= e($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <form action="save_incident.php" method="POST" class="incident-form">

        <div class="form-group">
            <label>Số phòng</label>
            <select name="RoomId" required>
                <option value="">-- Chọn phòng --</option>
                <?php while ($r = $rooms->fetch_assoc()): ?>
                    <option value="<?= e($r['RoomId']) ?>"><?= e($r['RoomNumber']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Người báo</label>
            <select name="ReportedBy" required>
                <option value="">-- Chọn nhân viên --</option>
                <?php while ($s = $staffs->fetch_assoc()): ?>
                    <option value="<?= e($s['StaffId']) ?>"><?= e($s['StaffName']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Tiêu đề sự cố</label>
            <input type="text" name="Title" required placeholder="VD: Máy lạnh không hoạt động">
        </div>

        <div class="form-group">
            <label>Mức độ nghiêm trọng</label>
            <select name="Severity">
                <option value="Thấp">Thấp</option>
                <option value="Trung bình" selected>Trung bình</option>
                <option value="Cao">Cao</option>
                <option value="Nghiêm trọng">Nghiêm trọng</option>
            </select>
        </div>

        <div class="form-group full">
            <label>Mô tả chi tiết</label>
            <textarea name="Description" placeholder="Mô tả chi tiết sự cố..."></textarea>
        </div>

        <button type="submit" class="submit-btn">Gửi báo cáo sự cố</button>
    </form>

</div>
</body>
</html>
