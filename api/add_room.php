<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['manager', 'admin']);

// lấy loại phòng
$types = $conn->query("SELECT * FROM room_type");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thêm phòng</title>
    <link rel="stylesheet" href="../add_staff.css">
</head>

<body>

<div class="form-container">
<h2>Thêm phòng</h2>

<form action="save_room.php" method="POST">

    <div class="input-group">
        <label>Số phòng</label>
        <input type="number" name="RoomNumber" required>
    </div>

    <div class="input-group">
        <label>Loại phòng</label>
        <select name="RoomTypeId">
            <?php while($t = $types->fetch_assoc()): ?>
            <option value="<?= e($t['RoomTypeId']) ?>">
                <?= e($t['RoomTypeName']) ?>
            </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="input-group">
        <label>Trạng thái</label>
        <select name="RoomStatus">
            <option value="Trống">Trống</option>
            <option value="Phòng đã đặt">Phòng đã đặt</option>
            <option value="Phòng đang thuê">Phòng đang thuê</option>
        </select>
    </div>

    <div class="input-group">
        <label>Ghi chú</label>
        <input type="text" name="Note">
    </div>

    <button type="submit">Thêm phòng</button>

</form>
</div>

</body>
</html>
