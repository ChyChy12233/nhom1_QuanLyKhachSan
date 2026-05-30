<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['manager', 'admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: invoice_list.php');
    exit;
}

$customerId  = trim($_POST['CustomerId']  ?? '');
$roomId      = trim($_POST['RoomId']      ?? '');
$checkIn     = trim($_POST['CheckIn']     ?? '');
$checkOut    = trim($_POST['CheckOut']    ?? '');
$totalAmount = (float)($_POST['TotalAmount'] ?? 0);
$status      = trim($_POST['Status']      ?? 'Chưa thanh toán');
$staffId     = $_SESSION['staff_id'] ?? null;

if (!$customerId || !$roomId || !$checkIn || !$checkOut || $totalAmount <= 0) {
    header('Location: add_invoice.php?error=' . urlencode('Vui lòng điền đầy đủ thông tin'));
    exit;
}

$id = gen_id('HD');

$stmt = $conn->prepare(
    "INSERT INTO invoice (InvoiceId, CustomerId, RoomId, CheckIn, CheckOut, TotalAmount, Status, StaffId)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param('sssssdss', $id, $customerId, $roomId, $checkIn, $checkOut, $totalAmount, $status, $staffId);

if ($stmt->execute()) {
    header('Location: invoice_list.php?ok=1');
} else {
    header('Location: add_invoice.php?error=' . urlencode($stmt->error));
}
exit;
