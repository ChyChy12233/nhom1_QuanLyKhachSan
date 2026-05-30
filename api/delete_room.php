<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['manager', 'admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: room_list.php');
    exit;
}

$id = trim($_POST['id'] ?? '');
if ($id === '') {
    header('Location: room_list.php?error=missing_id');
    exit;
}

$stmt = $conn->prepare("DELETE FROM room WHERE RoomId=?");
$stmt->bind_param('s', $id);

if ($stmt->execute()) {
    header('Location: room_list.php?ok=deleted');
} else {
    header('Location: room_list.php?error=' . urlencode($stmt->error));
}
exit;
