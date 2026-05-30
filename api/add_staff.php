<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['manager', 'admin']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Thêm nhân viên</title>
    <link rel="stylesheet" href="../add_staff.css">
</head>

<body>

<div class="form-container">

    <h2>Thêm nhân viên</h2>

    <form action="save_staff.php" method="POST">

        <div class="input-group">
            <label>Họ và tên</label>
            <input type="text" name="StaffName" required>
        </div>

        <div class="input-group">
            <label>SĐT</label>
            <input type="text" name="PhoneNumber" required>
        </div>

        <div class="input-group">
            <label>Email</label>
            <input type="email" name="Email" required>
        </div>

        <div class="input-group">
            <label>CCCD</label>
            <input type="text" name="CCCD" required>
        </div>

        <div class="input-group">
            <label>Ngày sinh</label>
            <input type="date" name="Birthday">
        </div>

        <div class="input-group">
            <label>Giới tính</label>
            <select name="Gender">
                <option value="Nam">Nam</option>
                <option value="Nữ">Nữ</option>
            </select>
        </div>

        <div class="input-group">
            <label>Chức vụ</label>
            <select name="Position">
                <option value="Nhân viên">Nhân viên</option>
                <option value="Quản lý">Quản lý</option>
            </select>
        </div>

        <div class="input-group">
            <label>Username</label>
            <input type="text" name="Username" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="Password" required>
        </div>

        <div class="input-group">
            <label>Role</label>
            <select name="Role">
                <option value="staff">Staff</option>
                <option value="manager">Manager</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <button type="submit">Thêm nhân viên</button>

    </form>

</div>

</body>
</html>
