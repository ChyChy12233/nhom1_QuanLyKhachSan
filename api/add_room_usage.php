<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

// Rooms available for check-in: not currently occupied
$roomsStmt = $conn->prepare(
    "SELECT r.RoomId, r.RoomNumber, r.RoomStatus, rt.RoomTypeName, rt.Price
     FROM room r
     JOIN room_type rt ON rt.RoomTypeId = r.RoomTypeId
     WHERE r.RoomStatus != 'Phòng đang thuê'
     ORDER BY r.RoomNumber"
);
$roomsStmt->execute();
$rooms = $roomsStmt->get_result();

$customersStmt = $conn->prepare("SELECT CustomerId, CustomerName, PhoneNumber FROM customer ORDER BY CustomerName");
$customersStmt->execute();
$customers = $customersStmt->get_result();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Check-in khách</title>
    <link rel="stylesheet" href="../add_staff.css">
</head>
<body>
<div class="form-container">
    <h2>Check-in khách</h2>

    <form action="save_room_usage.php" method="POST">

        <div class="input-group">
            <label>Khách hàng</label>
            <select name="CustomerId" required>
                <option value="">-- Chọn khách hàng --</option>
                <?php while ($c = $customers->fetch_assoc()): ?>
                <option value="<?= e($c['CustomerId']) ?>">
                    <?= e($c['CustomerName']) ?> — <?= e($c['PhoneNumber']) ?>
                </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="input-group">
            <label>Phòng</label>
            <select name="RoomId" required>
                <option value="">-- Chọn phòng --</option>
                <?php while ($r = $rooms->fetch_assoc()): ?>
                <option value="<?= e($r['RoomId']) ?>">
                    Phòng <?= e($r['RoomNumber']) ?> — <?= e($r['RoomTypeName']) ?>
                    (<?= format_money($r['Price']) ?>/đêm)
                    [<?= e($r['RoomStatus']) ?>]
                </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="input-group">
            <label>Ngày check-in</label>
            <input type="datetime-local" name="CheckIn" value="<?= date('Y-m-d\TH:i') ?>" required>
        </div>

        <button type="submit">Xác nhận Check-in</button>
        <a href="room_usage.php" style="margin-left:12px;color:#6b7280;font-size:14px;">← Quay lại</a>

    </form>
</div>
</body>
</html>
