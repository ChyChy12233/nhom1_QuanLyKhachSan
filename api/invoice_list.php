<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['manager', 'admin']);

$kw = trim($_GET['keyword'] ?? '');

// ── Status counts ─────────────────────────────────────────
$countPaid      = (int)$conn->query("SELECT COUNT(*) AS n FROM invoice WHERE Status = 'Đã thanh toán'")->fetch_assoc()['n'];
$countUnpaid    = (int)$conn->query("SELECT COUNT(*) AS n FROM invoice WHERE Status = 'Chưa thanh toán' OR Status IS NULL")->fetch_assoc()['n'];
$countCancelled = (int)$conn->query("SELECT COUNT(*) AS n FROM invoice WHERE Status = 'Đã hủy'")->fetch_assoc()['n'];

// ── Build WHERE ───────────────────────────────────────────
$where  = ['1=1'];
$params = [];
$types  = '';

if ($kw !== '') {
    $like     = '%' . $kw . '%';
    $where[]  = "(i.InvoiceId LIKE ? OR i.CustomerId LIKE ? OR i.StaffId LIKE ?)";
    $params[] = $like; $params[] = $like; $params[] = $like;
    $types   .= 'sss';
}

$whereSQL = implode(' AND ', $where);

$sql = "SELECT i.InvoiceId, i.CustomerId, i.StaffId, i.Status, i.TotalAmount, i.CreatedAt
        FROM invoice i
        WHERE $whereSQL
        ORDER BY i.CreatedAt DESC";

$stmt = $conn->prepare($sql);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// ── Badge helper ──────────────────────────────────────────
function badgeClass(string $status): string {
    if ($status === 'Đã thanh toán')   return 'badge badge-paid';
    if ($status === 'Đã hủy')          return 'badge badge-cancelled';
    return 'badge badge-unpaid';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý hóa đơn</title>
    <link rel="stylesheet" href="../invoice.css?v=<?= filemtime(__DIR__ . '/../invoice.css') ?>">
</head>
<body>

<!-- ── Page header ───────────────────────────────────────── -->
<div class="page-header">
    <h1>Quản lý hóa đơn</h1>
    <a href="add_invoice.php" class="btn-add">+ Thêm hóa đơn</a>
</div>

<?php if (isset($_GET['ok'])): ?>
    <div class="alert-ok">Tạo hóa đơn thành công!</div>
<?php elseif (isset($_GET['error'])): ?>
    <div class="alert-err">Lỗi: <?= e($_GET['error']) ?></div>
<?php endif; ?>

<!-- ── Status cards ──────────────────────────────────────── -->
<div class="status-cards">
    <div class="status-card card-paid">
        <div class="sc-label">Đã thanh toán</div>
        <div class="sc-count"><?= $countPaid ?></div>
    </div>
    <div class="status-card card-unpaid">
        <div class="sc-label">Chưa thanh toán</div>
        <div class="sc-count"><?= $countUnpaid ?></div>
    </div>
    <div class="status-card card-cancelled">
        <div class="sc-label">Đã hủy</div>
        <div class="sc-count"><?= $countCancelled ?></div>
    </div>
</div>

<!-- ── Filter bar ────────────────────────────────────────── -->
<form method="GET" class="filter-bar">
    <input type="text" name="keyword" placeholder="Tìm kiếm hóa đơn..." value="<?= e($kw) ?>">
    <button type="submit" class="btn-search">Tìm kiếm</button>
</form>

<!-- ── Table ─────────────────────────────────────────────── -->
<div class="invoice-table">
    <table>
        <thead>
            <tr>
                <th>Mã hóa đơn</th>
                <th>Mã khách hàng</th>
                <th>Mã nhân viên</th>
                <th>Trạng thái</th>
                <th>Thành tiền</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows === 0): ?>
            <tr><td colspan="6" class="empty-state">Chưa có hóa đơn nào</td></tr>
        <?php else: ?>
            <?php while ($row = $result->fetch_assoc()):
                $status = $row['Status'] ?? 'Chưa thanh toán';
            ?>
            <tr>
                <td><?= e($row['InvoiceId']) ?></td>
                <td><?= e($row['CustomerId'] ?? '—') ?></td>
                <td><?= e($row['StaffId'] ?? '—') ?></td>
                <td><span class="<?= badgeClass($status) ?>"><?= e($status) ?></span></td>
                <td><?= format_money($row['TotalAmount']) ?></td>
                <td><a href="view_invoice.php?id=<?= e($row['InvoiceId']) ?>" class="btn-detail">Chi tiết</a></td>
            </tr>
            <?php endwhile; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
