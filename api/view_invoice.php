<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['manager', 'admin']);

$id = trim($_GET['id'] ?? '');
if (!$id) { header('Location: invoice_list.php'); exit; }

$stmt = $conn->prepare(
    "SELECT i.*, c.CustomerName, c.PhoneNumber, r.RoomNumber, rt.RoomTypeName, rt.Price AS RoomPrice,
            s.StaffId AS SId, CONCAT(s.StaffId) AS StaffLabel
     FROM invoice i
     LEFT JOIN customer  c  ON i.CustomerId  = c.CustomerId
     LEFT JOIN room      r  ON i.RoomId      = r.RoomId
     LEFT JOIN room_type rt ON r.RoomTypeId  = rt.RoomTypeId
     LEFT JOIN staff     s  ON i.StaffId     = s.StaffId
     WHERE i.InvoiceId = ?"
);
$stmt->bind_param('s', $id);
$stmt->execute();
$inv = $stmt->get_result()->fetch_assoc();

if (!$inv) { header('Location: invoice_list.php'); exit; }

// Invoice detail lines
$dStmt = $conn->prepare("SELECT * FROM invoice_detail WHERE InvoiceId = ? ORDER BY InvoiceDetailId");
$dStmt->bind_param('s', $id);
$dStmt->execute();
$details = $dStmt->get_result()->fetch_all(MYSQLI_ASSOC);

$status = $inv['Status'] ?? 'Chưa thanh toán';
$badgeMap = [
    'Đã thanh toán'  => 'badge badge-paid',
    'Chưa thanh toán'=> 'badge badge-unpaid',
    'Đã hủy'         => 'badge badge-cancelled',
];
$badgeClass = $badgeMap[$status] ?? 'badge badge-unpaid';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết hóa đơn <?= e($id) ?></title>
    <link rel="stylesheet" href="../invoice.css?v=<?= filemtime(__DIR__ . '/../invoice.css') ?>">
    <style>
        .detail-card { background: white; border-radius: 12px; padding: 28px 32px; box-shadow: 0 2px 10px rgba(0,0,0,.06); margin-bottom: 20px; }
        .detail-card h2 { font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 18px; padding-bottom: 12px; border-bottom: 1px solid #f3f4f6; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px 32px; }
        .info-item label { font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.4px; display: block; margin-bottom: 4px; }
        .info-item span  { font-size: 15px; color: #111827; font-weight: 500; }
        .info-item.full  { grid-column: 1 / -1; }
        .total-row { display: flex; justify-content: space-between; align-items: center; padding: 14px 0; border-top: 2px solid #f3f4f6; margin-top: 8px; }
        .total-row .label { font-size: 15px; font-weight: 600; color: #374151; }
        .total-row .amount { font-size: 22px; font-weight: 800; color: #2563eb; }

        /* detail lines table */
        .lines-table { width: 100%; border-collapse: collapse; margin-top: 4px; }
        .lines-table th, .lines-table td { padding: 10px 12px; text-align: left; font-size: 14px; }
        .lines-table thead tr { background: #f8fafc; }
        .lines-table th { font-weight: 600; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.3px; }
        .lines-table tbody tr { border-top: 1px solid #f3f4f6; }
        .lines-table tbody tr:hover { background: #fafafa; }
        .no-lines { color: #9ca3af; font-size: 14px; padding: 12px 0; }
    </style>
</head>
<body>

<div class="page-header">
    <h1>Chi tiết hóa đơn</h1>
    <a href="invoice_list.php" class="btn-cancel">← Quay lại</a>
</div>

<!-- ── Thông tin hóa đơn ──────────────────────────────────── -->
<div class="detail-card">
    <h2>Thông tin hóa đơn</h2>
    <div class="info-grid">
        <div class="info-item">
            <label>Mã hóa đơn</label>
            <span><?= e($inv['InvoiceId']) ?></span>
        </div>
        <div class="info-item">
            <label>Trạng thái</label>
            <span><span class="<?= $badgeClass ?>"><?= e($status) ?></span></span>
        </div>
        <div class="info-item">
            <label>Mã khách hàng</label>
            <span><?= e($inv['CustomerId'] ?? '—') ?></span>
        </div>
        <div class="info-item">
            <label>Tên khách hàng</label>
            <span><?= e($inv['CustomerName'] ?? '—') ?></span>
        </div>
        <div class="info-item">
            <label>SĐT khách</label>
            <span><?= e($inv['PhoneNumber'] ?? '—') ?></span>
        </div>
        <div class="info-item">
            <label>Mã nhân viên</label>
            <span><?= e($inv['StaffId'] ?? '—') ?></span>
        </div>
        <div class="info-item">
            <label>Phòng</label>
            <span><?= e($inv['RoomNumber'] ?? '—') ?></span>
        </div>
        <div class="info-item">
            <label>Loại phòng</label>
            <span><?= e($inv['RoomTypeName'] ?? '—') ?></span>
        </div>
        <div class="info-item">
            <label>Check-in</label>
            <span><?= $inv['CheckIn']  ? e(format_date($inv['CheckIn']))  : '—' ?></span>
        </div>
        <div class="info-item">
            <label>Check-out</label>
            <span><?= $inv['CheckOut'] ? e(format_date($inv['CheckOut'])) : '—' ?></span>
        </div>
        <div class="info-item">
            <label>Ngày lập</label>
            <span><?= e(format_date($inv['CreatedAt'])) ?></span>
        </div>
    </div>

    <div class="total-row">
        <span class="label">Tổng tiền</span>
        <span class="amount"><?= format_money($inv['TotalAmount']) ?></span>
    </div>
</div>

<!-- ── Chi tiết dòng ─────────────────────────────────────── -->
<div class="detail-card">
    <h2>Chi tiết dịch vụ</h2>
    <?php if (empty($details)): ?>
        <p class="no-lines">Chưa có dòng chi tiết nào.</p>
    <?php else: ?>
        <table class="lines-table">
            <thead>
                <tr>
                    <th>Loại</th>
                    <th>Tên</th>
                    <th>SL</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($details as $d): ?>
                <tr>
                    <td><?= e($d['ItemType'] ?? '—') ?></td>
                    <td><?= e($d['ItemName'] ?? '—') ?></td>
                    <td><?= (int)$d['Quantity'] ?></td>
                    <td><?= format_money($d['UnitPrice']) ?></td>
                    <td><?= format_money($d['LineTotal']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
