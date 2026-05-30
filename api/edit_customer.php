<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

$id = $_GET['id'] ?? '';
$stmt = $conn->prepare("SELECT * FROM customer WHERE CustomerId = ?");
$stmt->bind_param('s', $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) {
    header('Location: customer_list.php?error=not_found');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sửa khách hàng</title>
    <link rel="stylesheet" href="../add_staff.css">
</head>

<body>

<div class="form-container">

<h2>Chỉnh sửa khách hàng</h2>

<form action="update_customer.php" method="POST">

    <input type="hidden" name="CustomerId" value="<?= e($row['CustomerId']) ?>">

    <div class="input-group">
        <label>Tên khách hàng</label>
        <input type="text" name="CustomerName" value="<?= e($row['CustomerName']) ?>" required>
    </div>

    <div class="input-group">
        <label>SĐT</label>
        <input type="text" name="PhoneNumber" value="<?= e($row['PhoneNumber']) ?>" required>
    </div>

    <div class="input-group">
        <label>Email</label>
        <input type="email" name="Email" value="<?= e($row['Email']) ?>" required>
    </div>

    <div class="input-group">
        <label>CCCD</label>
        <input type="text" name="CCCD" value="<?= e($row['CCCD']) ?>" required>
    </div>

    <div class="input-group">
        <label>Ngày sinh</label>
        <input type="date" name="Birthday" value="<?= e($row['Birthday']) ?>" required>
    </div>

    <div class="input-group">
        <label>Giới tính</label>
        <select name="Gender">
            <option value="Nam" <?= ($row['Gender']=="Nam")?'selected':'' ?>>Nam</option>
            <option value="Nữ" <?= ($row['Gender']=="Nữ")?'selected':'' ?>>Nữ</option>
        </select>
    </div>

    <div class="input-group">
        <label>Loại khách</label>
        <select name="CustomerType">
            <option value="Nội địa" <?= ($row['CustomerType']=="Nội địa")?'selected':'' ?>>Nội địa</option>
            <option value="Nước ngoài" <?= ($row['CustomerType']=="Nước ngoài")?'selected':'' ?>>Nước ngoài</option>
        </select>
    </div>

    <div class="input-group">
        <label>Địa chỉ</label>
        <input type="text" name="CustomerAddress" value="<?= e($row['CustomerAddress']) ?>" required>
    </div>

    <button type="submit">Cập nhật</button>

</form>

</div>

</body>
</html>
