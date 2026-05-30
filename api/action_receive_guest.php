<?php
// Nhận phòng: check-in khách đã có booking trước.
// GET ?booking_id=xxx  →  tạo room_customer, cập nhật booking + room, redirect về booking_list.
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

$bookingId = trim($_GET['booking_id'] ?? '');
if ($bookingId === '') {
    header('Location: booking_list.php?error=missing_booking_id');
    exit;
}

// Lấy booking + kiểm tra trạng thái
$stmt = $conn->prepare(
    "SELECT b.BookingId, b.RoomId, b.CustomerId, b.CheckInDate, b.CheckOutDate, b.Status, r.RoomStatus
     FROM booking b
     JOIN room r ON r.RoomId = b.RoomId
     WHERE b.BookingId = ?"
);
$stmt->bind_param('s', $bookingId);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

if (!$booking) {
    header('Location: booking_list.php?error=' . urlencode('Không tìm thấy đặt phòng'));
    exit;
}
if ($booking['Status'] !== 'Đã đặt') {
    header('Location: booking_list.php?error=' . urlencode('Đặt phòng không ở trạng thái "Đã đặt"'));
    exit;
}

$rcId    = gen_id('RC');
$checkIn = date('Y-m-d H:i:s');

$conn->begin_transaction();
try {
    // Tạo bản ghi sử dụng phòng
    $s1 = $conn->prepare(
        "INSERT INTO room_customer (RoomCustomerId, RoomId, CustomerId, CheckIn, Status)
         VALUES (?, ?, ?, ?, 'Đang ở')"
    );
    $s1->bind_param('ssss', $rcId, $booking['RoomId'], $booking['CustomerId'], $checkIn);
    $s1->execute();

    // Booking → Đang ở
    $s2 = $conn->prepare("UPDATE booking SET Status='Đang ở' WHERE BookingId=?");
    $s2->bind_param('s', $bookingId);
    $s2->execute();

    // Room → Phòng đang thuê
    $s3 = $conn->prepare("UPDATE room SET RoomStatus='Phòng đang thuê' WHERE RoomId=?");
    $s3->bind_param('s', $booking['RoomId']);
    $s3->execute();

    $conn->commit();
    header('Location: booking_list.php?ok=received');
} catch (Exception $e) {
    $conn->rollback();
    header('Location: booking_list.php?error=' . urlencode($e->getMessage()));
}
exit;
