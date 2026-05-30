<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

// lấy khách
$customers = $conn->query("SELECT * FROM customer ORDER BY CustomerName");

// lấy phòng trống
$rooms = $conn->query("SELECT * FROM room WHERE RoomStatus IN ('Trống','Phòng trống') ORDER BY RoomNumber");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Check-in</title>
    <link rel="stylesheet" href="../add_staff.css">
</head>

<body>

<div class="form-container">
<h2>Check-in khách</h2>

<form action="save_checkin.php" method="POST">

    <div class="input-group">
        <label>Khách hàng</label>
        <select name="CustomerId" required>
            <?php while($c = $customers->fetch_assoc()): ?>
            <option value="<?= e($c['CustomerId']) ?>">
                <?= e($c['CustomerName']) ?> - <?= e($c['PhoneNumber']) ?>
            </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="input-group">
        <label>Phòng</label>
        <select name="RoomId" required>
            <?php while($r = $rooms->fetch_assoc()): ?>
            <option value="<?= e($r['RoomId']) ?>">
                Phòng <?= e($r['RoomNumber']) ?>
            </option>
            <?php endwhile; ?>
        </select>
    </div>

    <button type="submit">Check-in</button>

</form>
</div>

</body>
</html>
