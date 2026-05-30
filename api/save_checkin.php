<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkin.php');
    exit;
}

$customerId = trim($_POST['CustomerId'] ?? '');
$roomId     = trim($_POST['RoomId'] ?? '');

if ($customerId === '' || $roomId === '') {
    header('Location: checkin.php?error=missing_fields');
    exit;
}

$id      = gen_id('RC');
$checkIn = date('Y-m-d H:i:s');

$conn->begin_transaction();
try {
    $stmt = $conn->prepare(
        "INSERT INTO room_customer (RoomCustomerId, RoomId, CustomerId, CheckIn, Status)
         VALUES (?, ?, ?, ?, 'Đang ở')"
    );
    $stmt->bind_param('ssss', $id, $roomId, $customerId, $checkIn);
    $stmt->execute();

    // Sync room status per §7 status table
    $roomStmt = $conn->prepare("UPDATE room SET RoomStatus='Phòng đang thuê' WHERE RoomId=?");
    $roomStmt->bind_param('s', $roomId);
    $roomStmt->execute();

    $conn->commit();
    header('Location: room_usage.php?ok=checked_in');
} catch (Exception $e) {
    $conn->rollback();
    header('Location: checkin.php?error=' . urlencode($e->getMessage()));
}
exit;
