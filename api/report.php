<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['manager', 'admin']);

$month = $_GET['month'] ?? '';
$year  = $_GET['year']  ?? '';

// ── Build WHERE ───────────────────────────────────────────
$where  = ['1=1'];
$params = [];
$types  = '';

if ($month !== '') {
    $where[]  = "MONTH(CreatedAt) = ?";
    $params[] = (int)$month;
    $types   .= 'i';
}
if ($year !== '') {
    $where[]  = "YEAR(CreatedAt) = ?";
    $params[] = (int)$year;
    $types   .= 'i';
}

$whereSQL = implode(' AND ', $where);

// ── Tổng doanh thu ────────────────────────────────────────
$stmtTotal = $conn->prepare("SELECT SUM(TotalAmount) AS total FROM invoice WHERE $whereSQL");
if ($types) $stmtTotal->bind_param($types, ...$params);
$stmtTotal->execute();
$total = (float)($stmtTotal->get_result()->fetch_assoc()['total'] ?? 0);

// ── Công suất phòng ───────────────────────────────────────
$totalRooms   = (int)$conn->query("SELECT COUNT(*) AS n FROM room")->fetch_assoc()['n'];
$occupiedRooms = (int)$conn->query("SELECT COUNT(*) AS n FROM room WHERE RoomStatus='Phòng đang thuê'")->fetch_assoc()['n'];
$occupancy = $totalRooms > 0 ? round($occupiedRooms / $totalRooms * 100) : 0;

// ── Doanh thu theo ngày (for chart + table) ───────────────
$stmtDaily = $conn->prepare(
    "SELECT DATE(CreatedAt) AS day, SUM(TotalAmount) AS total
     FROM invoice
     WHERE $whereSQL
     GROUP BY DATE(CreatedAt)
     ORDER BY day ASC"
);
if ($types) $stmtDaily->bind_param($types, ...$params);
$stmtDaily->execute();
$dailyResult = $stmtDaily->get_result();

$labels = [];
$data   = [];
$rows   = [];
while ($d = $dailyResult->fetch_assoc()) {
    $labels[] = $d['day'];
    $data[]   = (float)$d['total'];
    $rows[]   = $d;
}

// ── Số hóa đơn ───────────────────────────────────────────
$stmtCount = $conn->prepare("SELECT COUNT(*) AS n FROM invoice WHERE $whereSQL");
if ($types) $stmtCount->bind_param($types, ...$params);
$stmtCount->execute();
$invoiceCount = (int)$stmtCount->get_result()->fetch_assoc()['n'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Báo cáo thống kê</title>
    <link rel="stylesheet" href="../staff.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stat-row { display:flex;gap:16px;margin:16px 0; }
        .stat-card { background:white;border-radius:12px;padding:16px 20px;box-shadow:0 2px 8px rgba(0,0,0,.06);flex:1; }
        .stat-card h4 { font-size:13px;color:#6b7280;margin-bottom:4px; }
        .stat-card p  { font-size:24px;font-weight:700;color:#111827; }
        .stat-card .sub { font-size:12px;color:#9ca3af;margin-top:2px; }
    </style>
</head>
<body>
<div class="container">

    <h2>Báo cáo thống kê</h2>

    <!-- FILTER -->
    <form method="GET" class="search-box">
        <select name="month">
            <option value="">-- Tháng --</option>
            <?php for ($i = 1; $i <= 12; $i++): ?>
                <option value="<?= $i ?>" <?= (int)$month === $i ? 'selected' : '' ?>>Tháng <?= $i ?></option>
            <?php endfor; ?>
        </select>
        <select name="year">
            <option value="">-- Năm --</option>
            <?php for ($y = 2024; $y <= 2030; $y++): ?>
                <option value="<?= $y ?>" <?= (int)$year === $y ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
        </select>
        <button type="submit">Lọc</button>
    </form>

    <!-- SUMMARY CARDS -->
    <div class="stat-row">
        <div class="stat-card">
            <h4>Tổng doanh thu</h4>
            <p><?= format_money($total) ?></p>
            <div class="sub"><?= $month ? "Tháng $month" : 'Toàn bộ' ?><?= $year ? "/$year" : '' ?></div>
        </div>
        <div class="stat-card">
            <h4>Công suất phòng</h4>
            <p><?= $occupancy ?>%</p>
            <div class="sub"><?= $occupiedRooms ?>/<?= $totalRooms ?> phòng đang thuê</div>
        </div>
        <div class="stat-card">
            <h4>Số hóa đơn</h4>
            <p><?= $invoiceCount ?></p>
            <div class="sub"><?= $month ? "Tháng $month" : 'Toàn bộ' ?></div>
        </div>
    </div>

    <!-- CHART -->
    <?php if (!empty($labels)): ?>
    <canvas id="chart" height="80" style="margin:20px 0;"></canvas>
    <script>
    new Chart(document.getElementById('chart'), {
        type: 'line',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: <?= json_encode($data) ?>,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37,99,235,.1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true,
                pointRadius: 4
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { callback: v => v.toLocaleString('vi-VN') + ' ₫' } } }
        }
    });
    </script>
    <?php else: ?>
        <p style="color:#6b7280;margin:20px 0;">Không có dữ liệu doanh thu trong khoảng thời gian này.</p>
    <?php endif; ?>

    <!-- TABLE -->
    <?php if (!empty($rows)): ?>
    <h3 style="margin-top:24px;margin-bottom:12px;">Chi tiết theo ngày</h3>
    <table style="width:100%;border-collapse:collapse;">
        <tr style="background:#0a2540;color:white;">
            <th style="padding:10px;text-align:left;">Ngày</th>
            <th style="padding:10px;text-align:right;">Doanh thu</th>
        </tr>
        <?php foreach (array_reverse($rows) as $r): ?>
        <tr>
            <td style="padding:10px;border-bottom:1px solid #f3f4f6;"><?= e($r['day']) ?></td>
            <td style="padding:10px;border-bottom:1px solid #f3f4f6;text-align:right;font-weight:600;"><?= format_money($r['total']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>

</div>
</body>
</html>
