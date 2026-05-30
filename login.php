<?php
session_start();
require_once __DIR__ . '/config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// LẤY DỮ LIỆU
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$role     = $_POST['role'] ?? '';

// LẤY USER THEO USERNAME — dùng bảng `staff` (bảng `users` đã deprecated)
$stmt = $conn->prepare("SELECT StaffId, Username, Password, Role FROM staff WHERE Username = ?");
$stmt->bind_param('s', $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// KIỂM TRA CÓ USER KHÔNG
if ($user) {

    // CHECK PASSWORD
    if (password_verify($password, $user['Password'])) {

        // CHECK ROLE
        if ($user['Role'] != $role) {
            echo "<script>
                alert('Sai vai trò!');
                window.location='index.php';
            </script>";
            exit();
        }

        // LOGIN SUCCESS
        $_SESSION['user']     = $user['Username'];
        $_SESSION['role']     = $user['Role'];
        $_SESSION['staff_id'] = $user['StaffId'];

        header("Location: dashboard.php");
        exit();

    } else {
        echo "<script>
            alert('Sai mật khẩu!');
            window.location='index.php';
        </script>";
    }

} else {
    echo "<script>
        alert('Sai tài khoản!');
        window.location='index.php';
    </script>";
}
?>
