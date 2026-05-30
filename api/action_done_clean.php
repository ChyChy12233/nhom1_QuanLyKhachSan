<?php
// Xác nhận phòng đã dọn xong → đặt lại RoomStatus = 'Trống'.
// GET ?room_id=xxx
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

$roomId = trim($_GET['room_id'] ?? '');
if ($roomId === '') {
    header('Location: booking_list.php?error=missing_room_id');
    exit;
}

$stmt = $conn->prepare("UPDATE room SET RoomStatus='Trống' WHERE RoomId=? AND RoomStatus='Đang dọn'");
$stmt->bind_param('s', $roomId);
$stmt->execute();

if ($stmt->affected_rows === 0) {
    header('Location: booking_list.php?error=' . urlencode('Phòng không ở trạng thái "Đang dọn"'));
    exit;
}

header('Location: booking_list.php?ok=clean_done');
exit;
