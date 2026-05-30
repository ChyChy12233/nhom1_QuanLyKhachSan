<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: room_usage.php');
    exit;
}

$rcId = trim($_POST['id'] ?? '');
if ($rcId === '') {
    header('Location: room_usage.php?error=missing_id');
    exit;
}

// Get RoomId and CustomerId before updating
$getStmt = $conn->prepare("SELECT RoomId, CustomerId, Status FROM room_customer WHERE RoomCustomerId=?");
$getStmt->bind_param('s', $rcId);
$getStmt->execute();
$rc = $getStmt->get_result()->fetch_assoc();

if (!$rc || $rc['Status'] !== 'Đang ở') {
    header('Location: room_usage.php?error=invalid_record');
    exit;
}

$roomId     = $rc['RoomId'];
$customerId = $rc['CustomerId'];
$checkOut   = date('Y-m-d H:i:s');

$conn->begin_transaction();
try {
    // Mark room_customer as checked out
    $stmt = $conn->prepare(
        "UPDATE room_customer SET CheckOut=?, Status='Đã trả' WHERE RoomCustomerId=?"
    );
    $stmt->bind_param('ss', $checkOut, $rcId);
    $stmt->execute();

    // Free the room — canonical empty status is 'Trống' (DB default + lifecycle)
    $roomStmt = $conn->prepare("UPDATE room SET RoomStatus='Trống' WHERE RoomId=?");
    $roomStmt->bind_param('s', $roomId);
    $roomStmt->execute();

    // Sync booking status if there's an active booking for this room
    $bookingStmt = $conn->prepare(
        "UPDATE booking SET Status='Đã trả'
         WHERE RoomId=? AND CustomerId=? AND Status='Đang ở'"
    );
    $bookingStmt->bind_param('ss', $roomId, $customerId);
    $bookingStmt->execute();

    $conn->commit();
    header('Location: room_usage.php?ok=checked_out');
} catch (Exception $e) {
    $conn->rollback();
    header('Location: room_usage.php?error=' . urlencode($e->getMessage()));
}
exit;
