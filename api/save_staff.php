<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['manager', 'admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: staff_list.php');
    exit;
}

$name     = trim($_POST['StaffName'] ?? '');
$phone    = trim($_POST['PhoneNumber'] ?? '');
$email    = trim($_POST['Email'] ?? '');
$cccd     = trim($_POST['CCCD'] ?? '');
$birthday = $_POST['Birthday'] ?? null;
$gender   = $_POST['Gender'] ?? null;
$position = $_POST['Position'] ?? null;
$username = trim($_POST['Username'] ?? '');
$password = $_POST['Password'] ?? '';
$role     = $_POST['Role'] ?? 'staff';

if ($name === '' || $phone === '' || $email === '' || $cccd === '' || $username === '' || $password === '') {
    header('Location: staff_list.php?error=missing_fields');
    exit;
}

$hash = password_hash($password, PASSWORD_BCRYPT);
$id   = gen_id('NV');

$sql = "INSERT INTO staff
        (StaffId, StaffName, PhoneNumber, Email, CCCD, Birthday, Gender, Position, Username, Password, Role)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sssssssssss', $id, $name, $phone, $email, $cccd, $birthday, $gender, $position, $username, $hash, $role);

if ($stmt->execute()) {
    header('Location: staff_list.php?ok=added');
} else {
    header('Location: staff_list.php?error=' . urlencode($stmt->error));
}
exit;
