<?php
$conn = mysqli_connect("localhost","root","","hotel");

// LẤY KEYWORD + ROLE
$keyword = isset($_GET['keyword']) ? mysqli_real_escape_string($conn, $_GET['keyword']) : "";
$role = isset($_GET['role']) ? $_GET['role'] : "";

// QUERY
$sql = "SELECT * FROM staff WHERE 1";

if ($keyword != "") {
    $sql .= " AND (
        LOWER(StaffName) LIKE LOWER('%$keyword%') 
        OR LOWER(Username) LIKE LOWER('%$keyword%') 
        OR PhoneNumber LIKE '%$keyword%'
    )";
}

if ($role != "") {
    $sql .= " AND Role = '$role'";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Danh sách nhân viên</title>
    <link rel="stylesheet" href="../staff.css">
</head>

<body>

<div class="container">

    <h2>Danh sách nhân viên</h2>

    <a href="add_staff.php" class="add-btn">+ Thêm nhân viên</a>

    <!-- FORM SEARCH + FILTER -->
    <form method="GET" class="search-box">
        
        <input type="text" name="keyword" placeholder="Tìm nhân viên..."
               value="<?= $keyword ?>">

        <select name="role">
            <option value="">-- Tất cả vai trò --</option>
            <option value="admin" <?= ($role=='admin')?'selected':'' ?>>Admin</option>
            <option value="manager" <?= ($role=='manager')?'selected':'' ?>>Manager</option>
            <option value="staff" <?= ($role=='staff')?'selected':'' ?>>Staff</option>
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

        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['StaffId'] ?></td>
                <td><?= $row['StaffName'] ?></td>
                <td><?= $row['PhoneNumber'] ?></td>
                <td><?= $row['Position'] ?></td>
                <td><?= $row['Role'] ?></td>

                <td class="action">
                    <a href="edit_staff.php?id=<?= $row['StaffId'] ?>" class="edit">Sửa</a>
                    <a href="delete_staff.php?id=<?= $row['StaffId'] ?>" class="delete"
                       onclick="return confirm('Bạn chắc chắn muốn xóa?')">Xóa</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Không tìm thấy nhân viên</td>
            </tr>
        <?php endif; ?>
    </table>

</div>

</body>
</html>