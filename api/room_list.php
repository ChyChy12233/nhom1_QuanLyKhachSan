<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['manager', 'admin']);

$keyword = trim($_GET['keyword'] ?? '');
$typeId  = $_GET['type']   ?? '';
$status  = $_GET['status'] ?? '';

$where  = ['1=1'];
$params = [];
$types  = '';

if ($keyword !== '') {
    $where[]  = "r.RoomNumber LIKE ?";
    $params[] = '%' . $keyword . '%';
    $types   .= 's';
}
if ($typeId !== '') {
    $where[]  = "r.RoomTypeId = ?";
    $params[] = $typeId;
    $types   .= 's';
}
if ($status !== '') {
    $where[]  = "r.RoomStatus = ?";
    $params[] = $status;
    $types   .= 's';
}

$sql  = "SELECT r.*, rt.RoomTypeName, rt.Price
         FROM room r
         JOIN room_type rt ON r.RoomTypeId = rt.RoomTypeId
         WHERE " . implode(' AND ', $where) . "
         ORDER BY r.RoomNumber";
$stmt = $conn->prepare($sql);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$roomTypes = $conn->query("SELECT RoomTypeId, RoomTypeName FROM room_type ORDER BY RoomTypeName");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách phòng</title>
    <link rel="stylesheet" href="../staff.css">
</head>
<body>
<div class="container">

    <h2>Danh sách phòng</h2>

    <?php if (isset($_GET['ok'])): ?>
        <div style="background:#d1fae5;color:#065f46;padding:8px 14px;border-radius:8px;margin-bottom:12px;font-size:14px;">
            <?= ['added'=>'Thêm phòng thành công!','updated'=>'Cập nhật thành công!','deleted'=>'Đã xóa phòng.'][$_GET['ok']] ?? 'Thành công!' ?>
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div style="background:#fee2e2;color:#991b1b;padding:8px 14px;border-radius:8px;margin-bottom:12px;font-size:14px;">
            Lỗi: <?= e($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <a href="add_room.php" class="add-btn">+ Thêm phòng</a>

    <form method="GET" class="search-box">
        <input type="text" name="keyword" placeholder="Tìm số phòng..." value="<?= e($keyword) ?>">

        <select name="type">
            <option value="">-- Tất cả loại --</option>
            <?php while ($t = $roomTypes->fetch_assoc()): ?>
                <option value="<?= e($t['RoomTypeId']) ?>" <?= $typeId===$t['RoomTypeId']?'selected':'' ?>>
                    <?= e($t['RoomTypeName']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <select name="status">
            <option value="">-- Trạng thái --</option>
            <option value="Trống"           <?= $status==='Trống'           ?'selected':'' ?>>Trống</option>
            <option value="Phòng trống"     <?= $status==='Phòng trống'     ?'selected':'' ?>>Phòng trống</option>
            <option value="Phòng đang thuê" <?= $status==='Phòng đang thuê' ?'selected':'' ?>>Đang thuê</option>
            <option value="Phòng đã đặt"   <?= $status==='Phòng đã đặt'   ?'selected':'' ?>>Đã đặt</option>
        </select>

        <button type="submit">Tìm</button>
    </form>

    <div class="room-table">
        <div class="table-header">
            <div>Phòng</div>
            <div>Loại phòng</div>
            <div>Giá</div>
            <div>Trạng thái</div>
            <div>Ghi chú</div>
            <div>Action</div>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()):
                $sc = '';
                if ($row['RoomStatus'] === 'Phòng trống' || $row['RoomStatus'] === 'Trống') $sc = 'phong-trong';
                elseif ($row['RoomStatus'] === 'Phòng đang thuê') $sc = 'phong-dang-thue';
                elseif ($row['RoomStatus'] === 'Phòng đã đặt') $sc = 'phong-da-dat';
            ?>
            <div class="table-row">
                <div><?= e($row['RoomNumber']) ?></div>
                <div><?= e($row['RoomTypeName']) ?></div>
                <div><?= format_money($row['Price']) ?></div>
                <div><span class="status <?= $sc ?>"><?= e($row['RoomStatus']) ?></span></div>
                <div><?= $row['Note'] ? e($row['Note']) : '—' ?></div>
                <div class="action">
                    <a href="edit_room.php?id=<?= e($row['RoomId']) ?>" class="edit">Sửa</a>
                    <form method="POST" action="delete_room.php" style="display:inline"
                          onsubmit="return confirm('Xóa phòng <?= e($row['RoomNumber']) ?>?')">
                        <input type="hidden" name="id" value="<?= e($row['RoomId']) ?>">
                        <button type="submit" class="delete">Xóa</button>
                    </form>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="margin-top:15px;color:#6b7280;">Không tìm thấy phòng nào</div>
        <?php endif; ?>
    </div>

</div>
</body>
</html>
