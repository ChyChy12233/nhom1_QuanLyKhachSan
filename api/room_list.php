<?php
$conn = mysqli_connect("localhost","root","","hotel");

// GET dữ liệu search
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

$sql = "SELECT r.*, rt.RoomTypeName,rt.Price
        FROM room r
        JOIN room_type rt ON r.RoomTypeId = rt.RoomTypeId
        WHERE 1=1";

if ($keyword != '') {
    $sql .= " AND r.RoomNumber LIKE '%$keyword%'";
}

if ($type != '') {
    $sql .= " AND r.RoomTypeId = '$type'";
}

if ($status != '') {
    $sql .= " AND r.RoomStatus = '$status'";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Danh sách phòng</title>
    <link rel="stylesheet" href="../staff.css">
</head>

<body>

<div class="container">

    <h2>Danh sách phòng</h2>

    <a href="add_room.php" class="add-btn">+ Thêm phòng</a>

    <!-- SEARCH + FILTER -->
    <form method="GET" class="search-box">

        <input type="text" name="keyword" placeholder="Tìm số phòng..."
            value="<?= htmlspecialchars($keyword) ?>">

        <select name="type">
            <option value="">-- Tất cả loại --</option>

            <?php
            $typeQuery = mysqli_query($conn, "SELECT * FROM room_type");
            while($t = mysqli_fetch_assoc($typeQuery)):
            ?>
                <option value="<?= $t['RoomTypeId'] ?>"
                    <?= ($type == $t['RoomTypeId']) ? 'selected' : '' ?>>
                    <?= $t['RoomTypeName'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <select name="status">
            <option value="">-- Trạng thái --</option>
            <option value="Phòng trống" <?= ($status=='Phòng trống')?'selected':'' ?>>
                Phòng trống
            </option>
            <option value="Phòng đang thuê" <?= ($status=='Phòng đang thuê')?'selected':'' ?>>
                Đang thuê
            </option>
            <option value="Phòng đã đặt" <?= ($status=='Phòng đã đặt')?'selected':'' ?>>
                Đã đặt
            </option>
        </select>

        <button type="submit">Tìm</button>

    </form>

    <div class="room-table">

        <!-- HEADER -->
        <div class="table-header">
    <div>Phòng</div>
    <div>Loại phòng</div>
    <div>Giá</div> <!-- đưa lên đây -->
    <div>Trạng thái</div>
    <div>Ghi chú</div>
    <div>Action</div>
</div>

        <!-- DATA -->
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>

            <?php
            $statusClass = '';
            if ($row['RoomStatus'] == 'Phòng trống') $statusClass = 'phong-trong';
            if ($row['RoomStatus'] == 'Phòng đang thuê') $statusClass = 'phong-dang-thue';
            if ($row['RoomStatus'] == 'Phòng đã đặt') $statusClass = 'phong-da-dat';
            ?>

            <div class="table-row">

    <div><?= $row['RoomNumber'] ?></div>

    <div><?= $row['RoomTypeName'] ?></div>

    <!-- ✅ GIÁ Ở ĐÂY -->
    <div><?= number_format($row['Price']) ?> đ</div>

    <div>
        <span class="status <?= $statusClass ?>">
            <?= $row['RoomStatus'] ?>
        </span>
    </div>

    <div><?= $row['Note'] ? $row['Note'] : '—' ?></div>

    <div class="action">
        <a href="edit_room.php?id=<?= $row['RoomId'] ?>" class="edit">Sửa</a>
        <a href="delete_room.php?id=<?= $row['RoomId'] ?>" class="delete">Xóa</a>
    </div>

</div>

            <?php endwhile; ?>

        <?php else: ?>
            <div style="margin-top:15px;">Không tìm thấy phòng</div>
        <?php endif; ?>

    </div>

</div>

</body>
</html>