<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['manager', 'admin']);

$keyword = trim($_GET['keyword'] ?? '');
$role    = $_GET['role'] ?? '';

$where  = ['1=1'];
$params = [];
$types  = '';

if ($keyword !== '') {
    $like = '%' . $keyword . '%';
    $where[]  = "(StaffName LIKE ? OR Username LIKE ? OR PhoneNumber LIKE ?)";
    $params[] = $like; $params[] = $like; $params[] = $like;
    $types   .= 'sss';
}
if ($role !== '') {
    $where[]  = "Role = ?";
    $params[] = $role;
    $types   .= 's';
}

$sql  = "SELECT * FROM staff WHERE " . implode(' AND ', $where) . " ORDER BY StaffName";
$stmt = $conn->prepare($sql);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách nhân viên</title>
    <link rel="stylesheet" href="../staff.css">
</head>
<body>
<div class="container">

    <h2>Danh sách nhân viên</h2>

    <?php if (isset($_GET['ok'])): ?>
        <div style="background:#d1fae5;color:#065f46;padding:8px 14px;border-radius:8px;margin-bottom:12px;font-size:14px;">
            <?= ['added'=>'Thêm nhân viên thành công!','updated'=>'Cập nhật thành công!','deleted'=>'Đã xóa nhân viên.'][$_GET['ok']] ?? 'Thành công!' ?>
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div style="background:#fee2e2;color:#991b1b;padding:8px 14px;border-radius:8px;margin-bottom:12px;font-size:14px;">
            Lỗi: <?= e($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <a href="add_staff.php" class="add-btn">+ Thêm nhân viên</a>

    <form method="GET" class="search-box">
        <input type="text" name="keyword" placeholder="Tìm nhân viên..." value="<?= e($keyword) ?>">
        <select name="role">
            <option value="">-- Tất cả vai trò --</option>
            <option value="admin"   <?= $role==='admin'   ? 'selected':'' ?>>Admin</option>
            <option value="manager" <?= $role==='manager' ? 'selected':'' ?>>Manager</option>
            <option value="staff"   <?= $role==='staff'   ? 'selected':'' ?>>Staff</option>
        </select>
        <button type="submit">Tìm</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Họ và Tên</th>
            <th>SĐT</th>
            <th>Chức vụ</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= e($row['StaffId']) ?></td>
                <td><?= e($row['StaffName']) ?></td>
                <td><?= e($row['PhoneNumber']) ?></td>
                <td><?= e($row['Position'] ?? '—') ?></td>
                <td><?= e($row['Role']) ?></td>
                <td class="action">
                    <a href="edit_staff.php?id=<?= e($row['StaffId']) ?>" class="edit">Sửa</a>
                    <form method="POST" action="delete_staff.php" style="display:inline"
                          onsubmit="return confirm('Xóa nhân viên <?= e($row['StaffName']) ?>?')">
                        <input type="hidden" name="id" value="<?= e($row['StaffId']) ?>">
                        <button type="submit" class="delete">Xóa</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6" style="text-align:center;padding:20px;color:#6b7280;">Không tìm thấy nhân viên</td></tr>
        <?php endif; ?>
    </table>

</div>
</body>
</html>
