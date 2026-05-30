<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['manager', 'admin']);

$kw     = trim($_GET['q'] ?? '');
$status = $_GET['status'] ?? '';

$where  = ['1=1'];
$params = [];
$types  = '';

if ($kw !== '') {
    $where[]  = "f.FacilityName LIKE ?";
    $params[] = '%' . $kw . '%';
    $types   .= 's';
}
if ($status !== '') {
    $where[]  = "f.Status = ?";
    $params[] = $status;
    $types   .= 's';
}

$sql = "SELECT f.FacilityId, f.FacilityName, f.Status, f.Quantity, f.Note,
               r.RoomNumber
        FROM facility f
        LEFT JOIN room r ON f.RoomId = r.RoomId
        WHERE " . implode(' AND ', $where) . "
        ORDER BY f.FacilityName";

$stmt = $conn->prepare($sql);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$rooms = $conn->query("SELECT RoomId, RoomNumber FROM room ORDER BY RoomNumber");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý cơ sở vật chất</title>
    <link rel="stylesheet" href="../facility.css">
    <style>
        .alert-ok  { background:#d1fae5;color:#065f46;padding:8px 14px;border-radius:8px;margin-bottom:12px;font-size:14px; }
        .alert-err { background:#fee2e2;color:#991b1b;padding:8px 14px;border-radius:8px;margin-bottom:12px;font-size:14px; }
        .badge-active { background:#dcfce7;color:#15803d;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600; }
        .badge-broken { background:#fee2e2;color:#991b1b;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600; }
        .badge-other  { background:#f3f4f6;color:#374151;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600; }
        .delete-btn-form { display:inline; }
        .delete-btn { border:none;cursor:pointer;padding:5px 12px;background:#fee2e2;color:#dc2626;border-radius:6px;font-size:13px; }
        .delete-btn:hover { background:#fecaca; }
    </style>
</head>
<body>
<div class="facility-container">

    <h2>Quản lý cơ sở vật chất</h2>

    <?php if (isset($_GET['ok'])): ?>
        <div class="alert-ok">
            <?= ['added'=>'Thêm thành công!','updated'=>'Cập nhật thành công!','deleted'=>'Đã xóa.'][$_GET['ok']] ?? 'Thành công!' ?>
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert-err">Lỗi: <?= e($_GET['error']) ?></div>
    <?php endif; ?>

    <!-- TOP ACTIONS -->
    <div class="top-actions">
        <form method="GET" style="display:contents">
            <input type="text" name="q" placeholder="Tìm tiện nghi..." value="<?= e($kw) ?>">
            <select name="status" onchange="this.form.submit()">
                <option value="">Tất cả trạng thái</option>
                <option value="Hoạt động"  <?= $status==='Hoạt động'  ?'selected':'' ?>>Hoạt động</option>
                <option value="Hỏng"       <?= $status==='Hỏng'       ?'selected':'' ?>>Hỏng</option>
                <option value="Bảo trì"    <?= $status==='Bảo trì'    ?'selected':'' ?>>Bảo trì</option>
            </select>
            <button type="submit">Tìm</button>
        </form>
        <button class="add-btn" onclick="openAddForm()">+ Tiện nghi mới</button>
    </div>

    <!-- LAYOUT: TABLE + FORM PANEL -->
    <div id="facilityLayout" class="facility-layout">

        <!-- TABLE -->
        <div class="facility-table">
            <table>
                <thead>
                    <tr>
                        <th>Mã TN</th>
                        <th>Tên tiện nghi</th>
                        <th>Phòng</th>
                        <th>Số lượng</th>
                        <th>Trạng thái</th>
                        <th>Ghi chú</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result->num_rows === 0): ?>
                    <tr><td colspan="7" style="text-align:center;padding:20px;color:#6b7280;">Không có tiện nghi nào</td></tr>
                <?php else: ?>
                    <?php while ($row = $result->fetch_assoc()):
                        $badgeClass = match($row['Status']) {
                            'Hoạt động' => 'badge-active',
                            'Hỏng'      => 'badge-broken',
                            default     => 'badge-other',
                        };
                    ?>
                    <tr>
                        <td><?= e($row['FacilityId']) ?></td>
                        <td><?= e($row['FacilityName']) ?></td>
                        <td><?= e($row['RoomNumber'] ?? '—') ?></td>
                        <td><?= (int)$row['Quantity'] ?></td>
                        <td><span class="<?= $badgeClass ?>"><?= e($row['Status']) ?></span></td>
                        <td><?= e($row['Note'] ?? '—') ?></td>
                        <td>
                            <a href="#" class="edit-btn"
                               onclick="openEditForm(<?= htmlspecialchars(json_encode([
                                   'id'       => $row['FacilityId'],
                                   'name'     => $row['FacilityName'],
                                   'quantity' => $row['Quantity'],
                                   'status'   => $row['Status'],
                                   'note'     => $row['Note'] ?? '',
                               ]), ENT_QUOTES) ?>); return false;">
                                Sửa
                            </a>
                            <form class="delete-btn-form" method="POST" action="delete_facility.php"
                                  onsubmit="return confirm('Xóa tiện nghi <?= e($row['FacilityName']) ?>?')">
                                <input type="hidden" name="id" value="<?= e($row['FacilityId']) ?>">
                                <button type="submit" class="delete-btn">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- FORM PANEL -->
        <div id="facilityForm" class="facility-form">
            <h3 id="formTitle">Thêm tiện nghi</h3>

            <form id="facilityFormEl" action="save_facility.php" method="POST">
                <input type="hidden" id="facilityId" name="FacilityId" value="">

                <div class="form-group">
                    <label>Tên tiện nghi</label>
                    <input type="text" id="fieldName" name="FacilityName" required placeholder="VD: TV 55 inch">
                </div>

                <div class="form-group">
                    <label>Phòng (tuỳ chọn)</label>
                    <select id="fieldRoom" name="RoomId">
                        <option value="">-- Không gán phòng --</option>
                        <?php
                        // Reset rooms result
                        $rooms->data_seek(0);
                        while ($r = $rooms->fetch_assoc()): ?>
                            <option value="<?= e($r['RoomId']) ?>"><?= e($r['RoomNumber']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Số lượng</label>
                    <input type="number" id="fieldQuantity" name="Quantity" value="1" min="0">
                </div>

                <div class="form-group">
                    <label>Trạng thái</label>
                    <select id="fieldStatus" name="Status">
                        <option value="Hoạt động">Hoạt động</option>
                        <option value="Hỏng">Hỏng</option>
                        <option value="Bảo trì">Bảo trì</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Ghi chú</label>
                    <input type="text" id="fieldNote" name="Note" placeholder="Ghi chú (tuỳ chọn)">
                </div>

                <button type="submit" class="save-btn">Lưu</button>
                <button type="button" class="save-btn" onclick="closeForm()"
                        style="background:#6b7280;margin-top:8px;">Đóng</button>
            </form>
        </div>

    </div>

</div>

<script>
const layout = document.getElementById('facilityLayout');

function openAddForm() {
    document.getElementById('formTitle').textContent = 'Thêm tiện nghi';
    document.getElementById('facilityFormEl').action = 'save_facility.php';
    document.getElementById('facilityId').value  = '';
    document.getElementById('fieldName').value   = '';
    document.getElementById('fieldQuantity').value = '1';
    document.getElementById('fieldStatus').value = 'Hoạt động';
    document.getElementById('fieldNote').value   = '';
    layout.classList.add('show-form');
}

function openEditForm(data) {
    document.getElementById('formTitle').textContent = 'Sửa tiện nghi';
    document.getElementById('facilityFormEl').action = 'update_facility.php';
    document.getElementById('facilityId').value  = data.id;
    document.getElementById('fieldName').value   = data.name;
    document.getElementById('fieldQuantity').value = data.quantity;
    document.getElementById('fieldStatus').value = data.status;
    document.getElementById('fieldNote').value   = data.note;
    layout.classList.add('show-form');
}

function closeForm() {
    layout.classList.remove('show-form');
}
</script>
</body>
</html>
