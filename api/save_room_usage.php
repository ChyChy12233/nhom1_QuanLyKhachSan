<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: room_usage.php');
    exit;
}

$customerId = trim($_POST['CustomerId'] ?? '');
$roomId     = trim($_POST['RoomId'] ?? '');
$checkIn    = $_POST['CheckIn'] ?? date('Y-m-d H:i:s');

if ($customerId === '' || $roomId === '') {
    header('Location: add_room_usage.php?error=missing_fields');
    exit;
}

// Guard: ensure room is not already occupied
$checkStmt = $conn->prepare("SELECT RoomStatus FROM room WHERE RoomId=?");
$checkStmt->bind_param('s', $roomId);
$checkStmt->execute();
$checkRow = $checkStmt->get_result()->fetch_assoc();

if (!$checkRow || $checkRow['RoomStatus'] === 'Phòng đang thuê') {
    header('Location: add_room_usage.php?error=room_occupied');
    exit;
}

$id = gen_id('RC');

$conn->begin_transaction();
try {
    // INSERT room_customer
    $stmt = $conn->prepare(
        "INSERT INTO room_customer (RoomCustomerId, RoomId, CustomerId, CheckIn, Status)
         VALUES (?, ?, ?, ?, 'Đang ở')"
    );
    $stmt->bind_param('ssss', $id, $roomId, $customerId, $checkIn);
    $stmt->execute();

    // Sync room status
    $roomStmt = $conn->prepare("UPDATE room SET RoomStatus='Phòng đang thuê' WHERE RoomId=?");
    $roomStmt->bind_param('s', $roomId);
    $roomStmt->execute();

    // If there's a pending booking for this customer+room, mark it Đang ở
    $bookingStmt = $conn->prepare(
        "UPDATE booking SET Status='Đang ở'
         WHERE CustomerId=? AND RoomId=? AND Status='Đã đặt'
         ORDER BY CheckInDate ASC LIMIT 1"
    );
    $bookingStmt->bind_param('ss', $customerId, $roomId);
    $bookingStmt->execute();

    $conn->commit();
    header('Location: room_usage.php?ok=checked_in');
} catch (Exception $e) {
    $conn->rollback();
    header('Location: add_room_usage.php?error=' . urlencode($e->getMessage()));
}
exit;
