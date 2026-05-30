<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['manager', 'admin']);

// Load customers and rooms for dropdowns
$customers = $conn->query("SELECT CustomerId, CustomerName FROM customer ORDER BY CustomerName")->fetch_all(MYSQLI_ASSOC);
$rooms     = $conn->query("SELECT r.RoomId, r.RoomNumber, rt.RoomTypeName FROM room r LEFT JOIN room_type rt ON r.RoomTypeId = rt.RoomTypeId ORDER BY r.RoomNumber")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm hóa đơn</title>
    <link rel="stylesheet" href="../invoice.css?v=<?= filemtime(__DIR__ . '/../invoice.css') ?>">
</head>
<body>

<div class="page-header">
    <h1>Thêm hóa đơn</h1>
    <a href="invoice_list.php" class="btn-cancel">← Quay lại</a>
</div>

<?php if (isset($_GET['error'])): ?>
    <div class="alert-err">Lỗi: <?= e($_GET['error']) ?></div>
<?php endif; ?>

<div class="form-card">
    <form method="POST" action="save_invoice.php">

        <div class="form-group">
            <label>Khách hàng</label>
            <select name="CustomerId" required>
                <option value="">-- Chọn khách hàng --</option>
                <?php foreach ($customers as $c): ?>
                    <option value="<?= e($c['CustomerId']) ?>">
                        <?= e($c['CustomerId']) ?> — <?= e($c['CustomerName']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Phòng</label>
            <select name="RoomId" required>
                <option value="">-- Chọn phòng --</option>
                <?php foreach ($rooms as $r): ?>
                    <option value="<?= e($r['RoomId']) ?>">
                        <?= e($r['RoomNumber']) ?> (<?= e($r['RoomTypeName'] ?? '—') ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Ngày check-in</label>
                <input type="date" name="CheckIn" required>
            </div>
            <div class="form-group">
                <label>Ngày check-out</label>
                <input type="date" name="CheckOut" required>
            </div>
        </div>

        <div class="form-group">
            <label>Tổng tiền (VND)</label>
            <input type="number" name="TotalAmount" min="0" step="1000" placeholder="VD: 1500000" required>
        </div>

        <div class="form-group">
            <label>Trạng thái</label>
            <select name="Status">
                <option value="Chưa thanh toán">Chưa thanh toán</option>
                <option value="Đã thanh toán">Đã thanh toán</option>
                <option value="Đã hủy">Đã hủy</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Lưu hóa đơn</button>
            <a href="invoice_list.php" class="btn-cancel">Hủy</a>
        </div>

    </form>
</div>

</body>
</html>
