<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['manager', 'admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: room_list.php');
    exit;
}

$id         = trim($_POST['RoomId'] ?? '');
$roomNumber = trim($_POST['RoomNumber'] ?? '');
$roomTypeId = trim($_POST['RoomTypeId'] ?? '');
$roomStatus = $_POST['RoomStatus'] ?? 'Trống';
$note       = trim($_POST['Note'] ?? '');

if ($id === '' || $roomNumber === '' || $roomTypeId === '') {
    header('Location: room_list.php?error=missing_fields');
    exit;
}

$sql = "UPDATE room SET RoomNumber=?, RoomTypeId=?, RoomStatus=?, Note=? WHERE RoomId=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('issss', $roomNumber, $roomTypeId, $roomStatus, $note, $id);

if ($stmt->execute()) {
    header('Location: room_list.php?ok=updated');
} else {
    header('Location: room_list.php?error=' . urlencode($stmt->error));
}
exit;
