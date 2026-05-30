<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['manager', 'admin']);

$kw     = trim($_GET['keyword'] ?? '');
$status = $_GET['status'] ?? '';

$where  = ['1=1'];
$params = [];
$types  = '';

if ($kw !== '') {
    $like = '%' . $kw . '%';
    $where[]  = "(i.Title LIKE ? OR r.RoomNumber LIKE ?)";
    $params[] = $like; $params[] = $like;
    $types   .= 'ss';
}
if ($status !== '') {
    $where[]  = "i.Status = ?";
    $params[] = $status;
    $types   .= 's';
}

$sql = "SELECT i.IncidentId, i.Title, i.Description, i.Severity, i.Status,
               i.ReportedAt, i.ResolvedAt,
               r.RoomNumber, s.StaffName AS ReporterName
        FROM incident i
        LEFT JOIN room r  ON i.RoomId     = r.RoomId
        LEFT JOIN staff s ON i.ReportedBy = s.StaffId
        WHERE " . implode(' AND ', $where) . "
        ORDER BY i.ReportedAt DESC";

$stmt = $conn->prepare($sql);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Stats
$total    = (int)$conn->query("SELECT COUNT(*) AS n FROM incident")->fetch_assoc()['n'];
$pending  = (int)$conn->query("SELECT COUNT(*) AS n FROM incident WHERE Status IN ('Mới','Đang xử lý')")->fetch_assoc()['n'];
$resolved = (int)$conn->query("SELECT COUNT(*) AS n FROM incident WHERE Status = 'Đã xử lý'")->fetch_assoc()['n'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sự cố</title>
    <link rel="stylesheet" href="../incident.css">
    <style>
        .stats { display:flex;gap:20px;margin-bottom:18px; }
        .stat-box { background:white;border-radius:10px;padding:14px 20px;box-shadow:0 2px 8px rgba(0,0,0,.06);min-width:120px; }
        .stat-box h4 { font-size:13px;color:#6b7280;margin-bottom:4px; }
        .stat-box p  { font-size:22px;font-weight:700;color:#111827; }
        .badge-new      { background:#dbeafe;color:#1d4ed8;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600; }
        .badge-progress { background:#fef9c3;color:#a16207;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600; }
        .badge-done     { background:#dcfce7;color:#15803d;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600; }
        .sev-low    { color:#15803d; }
        .sev-med    { color:#a16207; }
        .sev-high   { color:#dc2626; }
        .alert-ok   { background:#d1fae5;color:#065f46;padding:8px 14px;border-radius:8px;margin-bottom:12px;font-size:14px; }
        .alert-err  { background:#fee2e2;color:#991b1b;padding:8px 14px;border-radius:8px;margin-bottom:12px;font-size:14px; }
    </style>
</head>
<body>
<div class="incident-container">

    <h2>Quản lý sự cố</h2>

    <?php if (isset($_GET['ok'])): ?>
        <div class="alert-ok">Cập nhật trạng thái thành công!</div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert-err">Lỗi: <?= e($_GET['error']) ?></div>
    <?php endif; ?>

    <!-- Stats -->
    <div class="stats">
        <div class="stat-box"><h4>Tổng sự cố</h4><p><?= $total ?></p></div>
        <div class="stat-box"><h4>Chờ xử lý</h4><p><?= $pending ?></p></div>
        <div class="stat-box"><h4>Đã xử lý</h4><p><?= $resolved ?></p></div>
    </div>

    <!-- Filter -->
    <div class="top-actions">
        <form method="GET" style="display:contents">
            <input type="text" name="keyword" placeholder="Tìm sự cố..." value="<?= e($kw) ?>">
            <select name="status" onchange="this.form.submit()">
                <option value="">Tất cả trạng thái</option>
                <option value="Mới"        <?= $status==='Mới'        ?'selected':'' ?>>Mới</option>
                <option value="Đang xử lý" <?= $status==='Đang xử lý' ?'selected':'' ?>>Đang xử lý</option>
                <option value="Đã xử lý"  <?= $status==='Đã xử lý'  ?'selected':'' ?>>Đã xử lý</option>
            </select>
            <button type="submit">Tìm</button>
        </form>
    </div>

    <!-- Table -->
    <table>
        <tr>
            <th>Mã SC</th>
            <th>Phòng</th>
            <th>Tiêu đề</th>
            <th>Mức độ</th>
            <th>Người báo</th>
            <th>Ngày báo</th>
            <th>Trạng thái</th>
            <th>Action</th>
        </tr>
        <?php if ($result->num_rows === 0): ?>
            <tr><td colspan="8" style="text-align:center;padding:20px;color:#6b7280;">Không có sự cố nào</td></tr>
        <?php else: ?>
        <?php while ($row = $result->fetch_assoc()):
            $badgeClass = match($row['Status']) {
                'Mới'        => 'badge-new',
                'Đang xử lý' => 'badge-progress',
                default      => 'badge-done',
            };
            $sevClass = match($row['Severity']) {
                'Thấp'     => 'sev-low',
                'Cao', 'Nghiêm trọng' => 'sev-high',
                default    => 'sev-med',
            };
        ?>
        <tr>
            <td><?= e($row['IncidentId']) ?></td>
            <td><?= e($row['RoomNumber'] ?? '—') ?></td>
            <td title="<?= e($row['Description'] ?? '') ?>"><?= e($row['Title']) ?></td>
            <td><span class="<?= $sevClass ?>"><?= e($row['Severity']) ?></span></td>
            <td><?= e($row['ReporterName'] ?? '—') ?></td>
            <td><?= e(format_date($row['ReportedAt'])) ?></td>
            <td><span class="<?= $badgeClass ?>"><?= e($row['Status']) ?></span></td>
            <td>
                <?php if ($row['Status'] !== 'Đã xử lý'): ?>
                <form method="POST" action="update_incident.php" style="display:inline">
                    <input type="hidden" name="id" value="<?= e($row['IncidentId']) ?>">
                    <button type="submit" class="edit-btn">Xử lý xong</button>
                </form>
                <?php else: ?>
                    <span style="color:#9ca3af;font-size:13px;">—</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
        <?php endif; ?>
    </table>

</div>
</body>
</html>
