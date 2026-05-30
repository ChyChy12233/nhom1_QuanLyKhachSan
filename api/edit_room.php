<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['manager', 'admin']);

$id = $_GET['id'] ?? '';

$stmt = $conn->prepare("SELECT * FROM room WHERE RoomId = ?");
$stmt->bind_param('s', $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) {
    header('Location: room_list.php?error=not_found');
    exit;
}

$types = $conn->query("SELECT * FROM room_type");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sửa phòng</title>
    <link rel="stylesheet" href="../add_staff.css">
</head>

<body>

<div class="form-container">

<h2>Sửa phòng</h2>

<form action="update_room.php" method="POST">

<input type="hidden" name="RoomId" value="<?= e($row['RoomId']) ?>">

<div class="input-group">
    <label>Số phòng</label>
    <input type="number" name="RoomNumber" value="<?= e($row['RoomNumber']) ?>">
</div>

<div class="input-group">
    <label>Loại phòng</label>
    <select name="RoomTypeId">
        <?php while($t = $types->fetch_assoc()): ?>
        <option value="<?= e($t['RoomTypeId']) ?>"
        <?= ($t['RoomTypeId']==$row['RoomTypeId'])?'selected':'' ?>>
        <?= e($t['RoomTypeName']) ?>
        </option>
        <?php endwhile; ?>
    </select>
</div>

<div class="input-group">
    <label>Trạng thái</label>
    <select name="RoomStatus">
        <option <?= (in_array($row['RoomStatus'], ['Trống','Phòng trống']))?'selected':'' ?>>Trống</option>
        <option <?= ($row['RoomStatus']=="Phòng đã đặt")?'selected':'' ?>>Phòng đã đặt</option>
        <option <?= ($row['RoomStatus']=="Phòng đang thuê")?'selected':'' ?>>Phòng đang thuê</option>
        <option <?= ($row['RoomStatus']=="Đang dọn")?'selected':'' ?>>Đang dọn</option>
    </select>
</div>

<div class="input-group">
    <label>Ghi chú</label>
    <input type="text" name="Note" value="<?= e($row['Note']) ?>">
</div>

<button type="submit">Cập nhật</button>

</form>

</div>
</body>
</html>
