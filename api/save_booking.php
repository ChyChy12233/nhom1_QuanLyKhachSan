<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: booking_checkin.php');
    exit;
}

$customerId  = trim($_POST['CustomerId'] ?? '');
$roomId      = trim($_POST['RoomId'] ?? '');
$checkIn     = $_POST['CheckInDate'] ?? '';
$checkOut    = $_POST['CheckOutDate'] ?? '';

if ($customerId === '' || $roomId === '' || $checkIn === '' || $checkOut === '') {
    header('Location: booking_checkin.php?error=missing_fields');
    exit;
}

// Conflict check: re-query room status inside handler before INSERT
$checkStmt = $conn->prepare(
    "SELECT BookingId FROM booking
     WHERE RoomId=? AND Status NOT IN ('Đã hủy')
     AND (? < CheckOutDate AND ? > CheckInDate)"
);
$checkStmt->bind_param('sss', $roomId, $checkIn, $checkOut);
$checkStmt->execute();
$checkStmt->store_result();
if ($checkStmt->num_rows > 0) {
    header('Location: booking_checkin.php?error=room_conflict');
    exit;
}

$id = gen_id('B');

$conn->begin_transaction();
try {
    $stmt = $conn->prepare(
        "INSERT INTO booking (BookingId, CustomerId, RoomId, CheckInDate, CheckOutDate, Status)
         VALUES (?, ?, ?, ?, ?, 'Đã đặt')"
    );
    $stmt->bind_param('sssss', $id, $customerId, $roomId, $checkIn, $checkOut);
    $stmt->execute();

    // Sync room status per §7 status table
    $roomStmt = $conn->prepare("UPDATE room SET RoomStatus='Phòng đã đặt' WHERE RoomId=?");
    $roomStmt->bind_param('s', $roomId);
    $roomStmt->execute();

    $conn->commit();
    header('Location: booking_list.php?ok=booked');
} catch (Exception $e) {
    $conn->rollback();
    header('Location: booking_checkin.php?error=' . urlencode($e->getMessage()));
}
exit;
