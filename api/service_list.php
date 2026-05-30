<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['manager', 'admin']);

$keyword = trim($_GET['q'] ?? '');

$sql = "SELECT ServiceId, ServiceName, Price, Unit, Note FROM service WHERE 1=1";
$params = [];
$types  = '';

if ($keyword !== '') {
    $sql    .= " AND ServiceName LIKE ?";
    $params[] = '%' . $keyword . '%';
    $types  .= 's';
}
$sql .= " ORDER BY ServiceName";

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
    <title>Quản lý dịch vụ</title>
    <link rel="stylesheet" href="../service.css">
    <style>
        .delete-btn-form { display:inline; }
        .delete-btn { border:none; cursor:pointer; padding:5px 12px; background:#fee2e2; color:#dc2626; border-radius:6px; font-size:13px; }
        .delete-btn:hover { background:#fecaca; }
        .alert-ok  { background:#d1fae5; color:#065f46; padding:8px 14px; border-radius:8px; margin-bottom:12px; font-size:14px; }
        .alert-err { background:#fee2e2; color:#991b1b; padding:8px 14px; border-radius:8px; margin-bottom:12px; font-size:14px; }
    </style>
</head>
<body>
<div class="service-container">

    <h2>Quản lý dịch vụ</h2>

    <?php if (isset($_GET['ok'])): ?>
        <div class="alert-ok">
            <?php
            $msg = ['added'=>'Thêm dịch vụ thành công!','updated'=>'Cập nhật thành công!','deleted'=>'Đã xóa dịch vụ.'];
            echo $msg[$_GET['ok']] ?? 'Thành công!';
            ?>
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert-err">Lỗi: <?= e($_GET['error']) ?></div>
    <?php endif; ?>

    <!-- TOP ACTIONS -->
    <div class="top-actions">
        <form method="GET" style="display:contents">
            <input type="text" name="q" placeholder="Tìm dịch vụ..." value="<?= e($keyword) ?>">
            <button type="submit">Tìm</button>
        </form>
        <button class="add-btn" onclick="openAddForm()">+ Dịch vụ mới</button>
    </div>

    <!-- SERVICE LAYOUT: table + sliding form panel -->
    <div id="serviceLayout" class="service-layout">

        <!-- LEFT: TABLE -->
        <div class="service-table">
            <table>
                <thead>
                    <tr>
                        <th>Mã DV</th>
                        <th>Tên dịch vụ</th>
                        <th>Giá</th>
                        <th>Đơn vị</th>
                        <th>Ghi chú</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result->num_rows === 0): ?>
                    <tr><td colspan="6" style="text-align:center;padding:20px;color:#6b7280;">Không có dịch vụ nào</td></tr>
                <?php else: ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= e($row['ServiceId']) ?></td>
                        <td><?= e($row['ServiceName']) ?></td>
                        <td><?= format_money($row['Price']) ?></td>
                        <td><?= e($row['Unit']) ?></td>
                        <td><?= e($row['Note'] ?? '—') ?></td>
                        <td>
                            <a href="#" class="edit-btn"
                               onclick="openEditForm(<?= htmlspecialchars(json_encode([
                                   'id'   => $row['ServiceId'],
                                   'name' => $row['ServiceName'],
                                   'price'=> $row['Price'],
                                   'unit' => $row['Unit'],
                                   'note' => $row['Note'] ?? '',
                               ]), ENT_QUOTES) ?>); return false;">
                                Sửa
                            </a>
                            <form class="delete-btn-form" method="POST" action="delete_service.php"
                                  onsubmit="return confirm('Xóa dịch vụ <?= e($row['ServiceName']) ?>?')">
                                <input type="hidden" name="id" value="<?= e($row['ServiceId']) ?>">
                                <button type="submit" class="delete-btn">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- RIGHT: SLIDING FORM -->
        <div id="serviceForm" class="service-form">

            <h3 id="formTitle">Thêm dịch vụ</h3>

            <form id="serviceFormEl" action="save_service.php" method="POST">
                <input type="hidden" id="serviceId" name="ServiceId" value="">

                <div class="form-group">
                    <label>Tên dịch vụ</label>
                    <input type="text" id="fieldName" name="ServiceName" required placeholder="VD: Giặt ủi">
                </div>

                <div class="form-group">
                    <label>Giá (VNĐ)</label>
                    <input type="number" id="fieldPrice" name="Price" required min="0" step="1000" placeholder="VD: 50000">
                </div>

                <div class="form-group">
                    <label>Đơn vị</label>
                    <input type="text" id="fieldUnit" name="Unit" value="lần" placeholder="lần / giờ / kg ...">
                </div>

                <div class="form-group">
                    <label>Ghi chú</label>
                    <input type="text" id="fieldNote" name="Note" placeholder="Ghi chú thêm (tuỳ chọn)">
                </div>

                <button type="submit" class="save-btn">Lưu dịch vụ</button>
                <button type="button" class="save-btn" onclick="closeForm()"
                        style="background:#6b7280;margin-top:8px;">Đóng</button>
            </form>

        </div>

    </div>

</div>

<script>
const layout = document.getElementById('serviceLayout');
const form   = document.getElementById('serviceForm');

function openAddForm() {
    document.getElementById('formTitle').textContent = 'Thêm dịch vụ';
    document.getElementById('serviceFormEl').action  = 'save_service.php';
    document.getElementById('serviceId').value  = '';
    document.getElementById('fieldName').value  = '';
    document.getElementById('fieldPrice').value = '';
    document.getElementById('fieldUnit').value  = 'lần';
    document.getElementById('fieldNote').value  = '';
    layout.classList.add('show-form');
}

function openEditForm(data) {
    document.getElementById('formTitle').textContent = 'Sửa dịch vụ';
    document.getElementById('serviceFormEl').action  = 'update_service.php';
    document.getElementById('serviceId').value  = data.id;
    document.getElementById('fieldName').value  = data.name;
    document.getElementById('fieldPrice').value = data.price;
    document.getElementById('fieldUnit').value  = data.unit;
    document.getElementById('fieldNote').value  = data.note;
    layout.classList.add('show-form');
}

function closeForm() {
    layout.classList.remove('show-form');
}
</script>
</body>
</html>
