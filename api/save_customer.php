<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: customer_list.php');
    exit;
}

// Map cả hai bộ field name: add_customer.php (PascalCase) và modal customer_list.php (lowercase)
$name    = trim($_POST['CustomerName'] ?? $_POST['name']  ?? '');
$phone   = trim($_POST['PhoneNumber']  ?? $_POST['phone'] ?? '');
$email   = trim($_POST['Email']        ?? $_POST['email'] ?? '');
$cccd    = trim($_POST['CCCD']         ?? $_POST['cccd']  ?? '');
$birthday = $_POST['Birthday']         ?? '';
$gender  = $_POST['Gender']            ?? null;
$type    = $_POST['CustomerType']      ?? $_POST['type']  ?? 'Nội địa';
$address = trim($_POST['CustomerAddress'] ?? $_POST['Address'] ?? '');
$nationality = trim($_POST['Nationality'] ?? $_POST['nationality'] ?? '');

// Validate NOT NULL fields
if ($name === '' || $phone === '' || $email === '') {
    header('Location: customer_list.php?error=missing_fields');
    exit;
}

// Birthday fallback (column is NOT NULL)
if ($birthday === '') {
    $birthday = date('Y-m-d');
}

$id = gen_id('KH');

$sql = "INSERT INTO customer
        (CustomerId, CustomerName, PhoneNumber, Email, CCCD, Birthday, Gender, CustomerType, CustomerAddress, Nationality)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ssssssssss', $id, $name, $phone, $email, $cccd, $birthday, $gender, $type, $address, $nationality);

if ($stmt->execute()) {
    header('Location: customer_list.php?ok=added');
} else {
    header('Location: customer_list.php?error=' . urlencode($stmt->error));
}
exit;
