<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['manager', 'admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: room_list.php');
    exit;
}

$roomNumber = trim($_POST['RoomNumber'] ?? '');
$roomTypeId = trim($_POST['RoomTypeId'] ?? '');
$roomStatus = $_POST['RoomStatus'] ?? 'Trống';
$note       = trim($_POST['Note'] ?? '');

if ($roomNumber === '' || $roomTypeId === '') {
    header('Location: room_list.php?error=missing_fields');
    exit;
}

$id = gen_id('R');

$sql = "INSERT INTO room (RoomId, RoomNumber, RoomTypeId, RoomStatus, Note)
        VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sisss', $id, $roomNumber, $roomTypeId, $roomStatus, $note);

if ($stmt->execute()) {
    header('Location: room_list.php?ok=added');
} else {
    header('Location: room_list.php?error=' . urlencode($stmt->error));
}
exit;
