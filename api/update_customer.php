<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: customer_list.php');
    exit;
}

$id      = trim($_POST['CustomerId'] ?? '');
$name    = trim($_POST['CustomerName'] ?? '');
$phone   = trim($_POST['PhoneNumber'] ?? '');
$email   = trim($_POST['Email'] ?? '');
$cccd    = trim($_POST['CCCD'] ?? '');
$birthday = $_POST['Birthday'] ?? '';
$gender  = $_POST['Gender'] ?? null;
$type    = $_POST['CustomerType'] ?? 'Nội địa';
$address = trim($_POST['CustomerAddress'] ?? '');

if ($id === '' || $name === '' || $phone === '' || $email === '') {
    header('Location: customer_list.php?error=missing_fields');
    exit;
}

if ($birthday === '') {
    $birthday = date('Y-m-d');
}

$sql = "UPDATE customer
        SET CustomerName=?, PhoneNumber=?, Email=?, CCCD=?, Birthday=?, Gender=?, CustomerType=?, CustomerAddress=?
        WHERE CustomerId=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sssssssss', $name, $phone, $email, $cccd, $birthday, $gender, $type, $address, $id);

if ($stmt->execute()) {
    header('Location: customer_list.php?ok=updated');
} else {
    header('Location: customer_list.php?error=' . urlencode($stmt->error));
}
exit;
