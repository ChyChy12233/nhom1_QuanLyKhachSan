<?php
require_once __DIR__ . '/../includes/auth.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm khách hàng</title>
    <link rel="stylesheet" href="../customer.css">
</head>

<body>

<div class="form-container">

  <h2>Thêm khách hàng</h2>

  <form class="form-grid" method="POST" action="save_customer.php">

    <div class="form-group">
      <label>Họ và Tên khách hàng</label>
      <input type="text" name="CustomerName" required>
    </div>

    <div class="form-group">
      <label>SĐT</label>
      <input type="text" name="PhoneNumber" required>
    </div>

    <div class="form-group">
      <label>Email</label>
      <input type="email" name="Email">
    </div>

    <div class="form-group">
      <label>CCCD</label>
      <input type="text" name="CCCD" required>
    </div>

    <div class="form-group">
      <label>Ngày sinh</label>
      <input type="date" name="Birthday">
    </div>

    <div class="form-group">
      <label>Giới tính</label>
      <select name="Gender">
        <option value="Nam">Nam</option>
        <option value="Nữ">Nữ</option>
      </select>
    </div>

    <div class="form-group">
      <label>Loại khách</label>
      <select name="CustomerType">
        <option value="Nội địa">Nội địa</option>
        <option value="Nước ngoài">Nước ngoài</option>
      </select>
    </div>

    <div class="form-group full">
      <label>Địa chỉ</label>
      <input type="text" name="Address">
    </div>

    <button type="submit" class="submit-btn">Thêm khách</button>

  </form>

</div>

</body>
</html>
