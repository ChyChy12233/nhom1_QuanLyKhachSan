<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['manager', 'admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: staff_list.php');
    exit;
}

$id       = trim($_POST['StaffId'] ?? '');
$name     = trim($_POST['StaffName'] ?? '');
$phone    = trim($_POST['PhoneNumber'] ?? '');
$email    = trim($_POST['Email'] ?? '');
$cccd     = trim($_POST['CCCD'] ?? '');
$birthday = $_POST['Birthday'] ?? null;
$gender   = $_POST['Gender'] ?? null;
$position = $_POST['Position'] ?? null;
$username = trim($_POST['Username'] ?? '');
$role     = $_POST['Role'] ?? 'staff';

if ($id === '' || $name === '' || $phone === '' || $email === '' || $username === '') {
    header('Location: staff_list.php?error=missing_fields');
    exit;
}

// Password not in edit form — keep existing password unchanged
$sql = "UPDATE staff
        SET StaffName=?, PhoneNumber=?, Email=?, CCCD=?, Birthday=?, Gender=?, Position=?, Username=?, Role=?
        WHERE StaffId=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ssssssssss', $name, $phone, $email, $cccd, $birthday, $gender, $position, $username, $role, $id);

if ($stmt->execute()) {
    header('Location: staff_list.php?ok=updated');
} else {
    header('Location: staff_list.php?error=' . urlencode($stmt->error));
}
exit;
