<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

// ── Read filter params (GET) ──────────────────────────────
$checkIn    = $_GET['CheckInDate']  ?? '';
$checkOut   = $_GET['CheckOutDate'] ?? '';
$custId     = $_GET['CustomerId']   ?? '';
$roomTypeId = $_GET['RoomTypeId']   ?? '';

// ── Customers dropdown ────────────────────────────────────
$custResult = $conn->query("SELECT CustomerId, CustomerName, StayCount FROM customer ORDER BY CustomerName");

// ── Room types dropdown ───────────────────────────────────
$rtResult = $conn->query("SELECT RoomTypeId, RoomTypeName FROM room_type ORDER BY RoomTypeName");

// ── Selected customer info ────────────────────────────────
$selectedCustomer = null;
if ($custId !== '') {
    $stmt = $conn->prepare("SELECT * FROM customer WHERE CustomerId=?");
    $stmt->bind_param('s', $custId);
    $stmt->execute();
    $selectedCustomer = $stmt->get_result()->fetch_assoc();
}

// ── Customer level & discount ─────────────────────────────
$customerLevel = 'New';
$discount      = 0;
if ($selectedCustomer) {
    if ($selectedCustomer['StayCount'] >= 10) { $customerLevel = 'VIP';     $discount = 20; }
    elseif ($selectedCustomer['StayCount'] >= 5) { $customerLevel = 'Regular'; $discount = 5;  }
}

// ── Available rooms (filtered by type + date conflict) ────
$availableRooms = [];
if ($checkIn && $checkOut && $roomTypeId) {
    $stmt = $conn->prepare(
        "SELECT r.RoomId, r.RoomNumber, rt.Price
         FROM room r
         JOIN room_type rt ON r.RoomTypeId = rt.RoomTypeId
         WHERE r.RoomTypeId = ?
           AND r.RoomId NOT IN (
               SELECT RoomId FROM booking
               WHERE Status NOT IN ('Đã hủy')
                 AND ? < CheckOutDate
                 AND ? > CheckInDate
           )"
    );
    $stmt->bind_param('sss', $roomTypeId, $checkIn, $checkOut);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($r = $res->fetch_assoc()) $availableRooms[] = $r;
}

// ── Price calculation ─────────────────────────────────────
$roomPrice   = 0;
$days        = 0;
$subTotal    = 0;
$discountAmt = 0;
$totalPrice  = 0;

if ($checkIn && $checkOut && $roomTypeId && $selectedCustomer) {
    $pStmt = $conn->prepare("SELECT Price FROM room_type WHERE RoomTypeId=?");
    $pStmt->bind_param('s', $roomTypeId);
    $pStmt->execute();
    $pRow = $pStmt->get_result()->fetch_assoc();
    if ($pRow) {
        $roomPrice   = (float)$pRow['Price'];
        $days        = max(0, (int)(( strtotime($checkOut) - strtotime($checkIn) ) / 86400));
        $subTotal    = $roomPrice * $days;
        $discountAmt = $subTotal * $discount / 100;
        $totalPrice  = $subTotal - $discountAmt;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt phòng mới</title>
    <link rel="stylesheet" href="../booking.css">
</head>
<body>
<div class="container">
    <div style="margin-bottom:16px;">
        <a href="booking_list.php"
           style="display:inline-flex;align-items:center;gap:6px;font-size:14px;color:#6b7280;text-decoration:none;">
            &#8592; Quay lại danh sách
        </a>
    </div>
    <h2>Đặt phòng mới</h2>

    <?php if (isset($_GET['ok'])): ?>
        <p style="background:#d1fae5;color:#065f46;padding:10px;border-radius:8px;margin-bottom:12px;">
            Đặt phòng thành công!
        </p>
    <?php elseif (isset($_GET['error'])): ?>
        <p style="background:#fee2e2;color:#991b1b;padding:10px;border-radius:8px;margin-bottom:12px;">
            Lỗi: <?= e($_GET['error']) ?>
        </p>
    <?php endif; ?>

    <!-- Bước 1: lọc phòng trống (GET) -->
    <form method="GET">
        <label>Khách hàng</label>
        <select name="CustomerId" required onchange="this.form.submit()">
            <option value="">-- Chọn khách --</option>
            <?php while ($c = $custResult->fetch_assoc()):
                $lvl = $c['StayCount'] >= 10 ? 'VIP' : ($c['StayCount'] >= 5 ? 'Regular' : 'New');
            ?>
                <option value="<?= e($c['CustomerId']) ?>" <?= $custId === $c['CustomerId'] ? 'selected' : '' ?>>
                    <?= e($c['CustomerName']) ?> (<?= $lvl ?>)
                </option>
            <?php endwhile; ?>
        </select>

        <label>Ngày nhận phòng</label>
        <input type="date" name="CheckInDate"  required value="<?= e($checkIn) ?>">

        <label>Ngày trả phòng</label>
        <input type="date" name="CheckOutDate" required value="<?= e($checkOut) ?>">

        <label>Loại phòng</label>
        <select name="RoomTypeId" required onchange="this.form.submit()">
            <option value="">-- Chọn loại phòng --</option>
            <?php while ($rt = $rtResult->fetch_assoc()): ?>
                <option value="<?= e($rt['RoomTypeId']) ?>" <?= $roomTypeId === $rt['RoomTypeId'] ? 'selected' : '' ?>>
                    <?= e($rt['RoomTypeName']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Tìm phòng trống</button>
    </form>

    <!-- Thông tin khách -->
    <?php if ($selectedCustomer): ?>
    <div class="customer-info">
        <span class="badge <?= strtolower($customerLevel) ?>"><?= $customerLevel ?></span>
        <p>Số lần lưu trú: <?= (int)$selectedCustomer['StayCount'] ?></p>
        <p>Tổng chi tiêu: <?= format_money($selectedCustomer['TotalSpent']) ?></p>
        <div class="voucher-box">
            <strong>Voucher đề xuất</strong>
            <p>
            <?php
                if ($customerLevel === 'VIP')     echo 'Giảm 20% phòng VIP';
                elseif ($customerLevel === 'Regular') echo 'Giảm 5% tất cả phòng';
                else echo 'Chưa có voucher';
            ?>
            </p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Bước 2: Xác nhận đặt phòng (POST) -->
    <?php if (!empty($availableRooms)): ?>
    <div class="payment-box">
        <h3>Thông tin thanh toán</h3>
        <?php if ($roomPrice > 0): ?>
        <p>Giá phòng: <?= format_money($roomPrice) ?></p>
        <p>Số đêm: <?= $days ?></p>
        <p>Tạm tính: <?= format_money($subTotal) ?></p>
        <p>Giảm giá: -<?= $discount ?>%</p>
        <h2>Tổng: <?= format_money($totalPrice) ?></h2>
        <?php endif; ?>
    </div>

    <form method="POST" action="save_booking.php">
        <input type="hidden" name="CustomerId"   value="<?= e($custId) ?>">
        <input type="hidden" name="CheckInDate"  value="<?= e($checkIn) ?>">
        <input type="hidden" name="CheckOutDate" value="<?= e($checkOut) ?>">

        <label>Chọn phòng</label>
        <select name="RoomId" required>
            <option value="">-- Chọn phòng --</option>
            <?php foreach ($availableRooms as $ar): ?>
                <option value="<?= e($ar['RoomId']) ?>">
                    Phòng <?= e($ar['RoomNumber']) ?> — <?= format_money($ar['Price']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Phương thức thanh toán</label>
        <select name="PaymentMethod">
            <option value="">-- Chọn phương thức --</option>
            <option value="Cash">Tiền mặt</option>
            <option value="Banking">Chuyển khoản</option>
            <option value="Card">Thẻ tín dụng</option>
            <option value="Momo">Ví Momo</option>
        </select>

        <button type="submit" style="width:100%;padding:10px;background:#2563eb;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:15px;margin-top:10px;">
            Xác nhận đặt phòng
        </button>
    </form>
    <?php elseif ($checkIn && $checkOut && $roomTypeId): ?>
        <p style="color:#ef4444;margin-top:12px;">Không còn phòng trống trong khoảng thời gian này.</p>
    <?php endif; ?>

</div>
</body>
</html>
