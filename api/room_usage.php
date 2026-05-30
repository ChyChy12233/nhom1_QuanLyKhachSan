<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

$status  = $_GET['status'] ?? '';
$keyword = trim($_GET['q'] ?? '');

// Summary counts (real DB)
$totalRooms      = $conn->query("SELECT COUNT(*) as n FROM room")->fetch_assoc()['n'];
$usingCount      = $conn->query("SELECT COUNT(*) as n FROM room_customer WHERE Status='Đang ở'")->fetch_assoc()['n'];
$emptyCount      = $conn->query("SELECT COUNT(*) as n FROM room WHERE RoomStatus NOT IN ('Phòng đang thuê','Phòng đã đặt')")->fetch_assoc()['n'];
$checkedOutToday = $conn->query("SELECT COUNT(*) as n FROM room_customer WHERE Status='Đã trả' AND DATE(CheckOut)=CURDATE()")->fetch_assoc()['n'];

// Main query: room_customer JOIN room + customer + room_type
$sql = "SELECT rc.RoomCustomerId, r.RoomNumber, rt.RoomTypeName, rt.Price,
               c.CustomerName, rc.CheckIn, rc.CheckOut, rc.Status
        FROM room_customer rc
        LEFT JOIN room r      ON r.RoomId      = rc.RoomId
        LEFT JOIN customer c  ON c.CustomerId  = rc.CustomerId
        LEFT JOIN room_type rt ON rt.RoomTypeId = r.RoomTypeId
        WHERE 1=1";

$params = [];
$types  = '';

if ($status !== '') {
    $sql    .= " AND rc.Status = ?";
    $params[] = $status;
    $types  .= 's';
}

if ($keyword !== '') {
    $sql    .= " AND r.RoomNumber LIKE ?";
    $params[] = '%' . $keyword . '%';
    $types  .= 's';
}

$sql .= " ORDER BY rc.CheckIn DESC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sử dụng phòng</title>
    <link rel="stylesheet" href="../room_usage.css">
    <style>
        .btn-checkin { display:inline-block; padding:8px 16px; background:#0a2540; color:#fff; border:none; border-radius:8px; cursor:pointer; font-size:14px; text-decoration:none; }
        .btn-checkin:hover { background:#163a60; }
        .btn-checkout { padding:5px 12px; background:#ef4444; color:#fff; border:none; border-radius:6px; cursor:pointer; font-size:13px; }
        .btn-checkout:hover { background:#dc2626; }
        .done-status { display:inline-block; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600; background:#e0f2fe; color:#075985; }
        .alert-ok  { background:#d1fae5; color:#065f46; padding:8px 14px; border-radius:8px; margin-bottom:12px; font-size:14px; }
        .alert-err { background:#fee2e2; color:#991b1b; padding:8px 14px; border-radius:8px; margin-bottom:12px; font-size:14px; }
    </style>
</head>
<body>
<div class="room-container">

    <h2>Quản lý sử dụng phòng</h2>

    <?php if (isset($_GET['ok'])): ?>
        <div class="alert-ok">
            <?php
            $msg = ['checked_in' => 'Check-in thành công!', 'checked_out' => 'Check-out thành công!'];
            echo $msg[$_GET['ok']] ?? 'Thành công!';
            ?>
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert-err">Lỗi: <?= e($_GET['error']) ?></div>
    <?php endif; ?>

    <!-- TOP ACTIONS -->
    <div class="top-actions">
        <form method="GET" style="display:contents">
            <input type="text" name="q" placeholder="Tìm số phòng..." value="<?= e($keyword) ?>">
            <select name="status" onchange="this.form.submit()">
                <option value="">Tất cả trạng thái</option>
                <option value="Đang ở"  <?= $status==='Đang ở'  ? 'selected' : '' ?>>Đang sử dụng</option>
                <option value="Đã trả"  <?= $status==='Đã trả'  ? 'selected' : '' ?>>Đã trả</option>
            </select>
            <button type="submit">Tìm kiếm</button>
        </form>
        <a href="add_room_usage.php" class="btn-checkin">+ Check-in mới</a>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="summary-cards">
        <div class="card total">
            <h3>Tổng số phòng</h3>
            <p><?= $totalRooms ?></p>
        </div>
        <div class="card using">
            <h3>Đang sử dụng</h3>
            <p><?= $usingCount ?></p>
        </div>
        <div class="card empty">
            <h3>Phòng trống</h3>
            <p><?= $emptyCount ?></p>
        </div>
        <div class="card cleaning">
            <h3>Check-out hôm nay</h3>
            <p><?= $checkedOutToday ?></p>
        </div>
    </div>

    <!-- TABLE -->
    <div class="room-table">
        <table>
            <thead>
                <tr>
                    <th>Số phòng</th>
                    <th>Loại phòng</th>
                    <th>Khách hàng</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Giá/đêm</th>
                    <th>Trạng thái</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result->num_rows === 0): ?>
                <tr><td colspan="8" style="text-align:center;padding:20px;color:#6b7280;">Không có dữ liệu</td></tr>
            <?php else: ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= e($row['RoomNumber']) ?></td>
                    <td><?= e($row['RoomTypeName']) ?></td>
                    <td><?= e($row['CustomerName']) ?></td>
                    <td><?= e(format_date($row['CheckIn'])) ?></td>
                    <td><?= $row['CheckOut'] ? e(format_date($row['CheckOut'])) : '—' ?></td>
                    <td><?= format_money($row['Price']) ?></td>
                    <td>
                        <?php if ($row['Status'] === 'Đang ở'): ?>
                            <span class="using-status">Đang sử dụng</span>
                        <?php else: ?>
                            <span class="done-status">Đã trả</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['Status'] === 'Đang ở'): ?>
                        <form method="POST" action="checkout_room_usage.php" style="display:inline"
                              onsubmit="return confirm('Check-out phòng <?= e($row['RoomNumber']) ?> của <?= e($row['CustomerName']) ?>?')">
                            <input type="hidden" name="id" value="<?= e($row['RoomCustomerId']) ?>">
                            <button type="submit" class="btn-checkout">Check-out</button>
                        </form>
                        <?php else: ?>
                            <span style="color:#9ca3af;font-size:13px;">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
