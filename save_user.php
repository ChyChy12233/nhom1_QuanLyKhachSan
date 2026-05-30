<?php
// Tạo tài khoản nhân viên — INSERT vào bảng `staff` (không dùng bảng deprecated `users`).
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/auth.php';
require_role(['admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: create_user.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$role     = $_POST['role'] ?? 'staff';

if ($username === '' || $password === '') {
    echo "<script>alert('Vui lòng điền đầy đủ thông tin!'); history.back();</script>";
    exit;
}

// Kiểm tra trùng username
$checkStmt = $conn->prepare("SELECT StaffId FROM staff WHERE Username = ?");
$checkStmt->bind_param('s', $username);
$checkStmt->execute();
$checkStmt->store_result();
if ($checkStmt->num_rows > 0) {
    echo "<script>alert('Username đã tồn tại!'); history.back();</script>";
    exit;
}

$id   = gen_id('NV');
$hash = password_hash($password, PASSWORD_BCRYPT);
// StaffName và các trường khác để trống — admin có thể edit sau
$name = $username;

$stmt = $conn->prepare(
    "INSERT INTO staff (StaffId, StaffName, PhoneNumber, Email, CCCD, Username, Password, Role)
     VALUES (?, ?, '', '', '', ?, ?, ?)"
);
$stmt->bind_param('sssss', $id, $name, $username, $hash, $role);

if ($stmt->execute()) {
    echo "<script>alert('Tạo tài khoản thành công!'); window.location='api/staff_list.php';</script>";
} else {
    echo "<script>alert('Lỗi: " . addslashes($stmt->error) . "'); history.back();</script>";
}
