<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['manager', 'admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: facility.php');
    exit;
}

$name     = trim($_POST['FacilityName'] ?? '');
$roomId   = trim($_POST['RoomId']       ?? '') ?: null;
$quantity = (int)($_POST['Quantity']    ?? 1);
$status   = $_POST['Status']  ?? 'Hoạt động';
$note     = trim($_POST['Note'] ?? '') ?: null;

if ($name === '') {
    header('Location: facility.php?error=missing_fields');
    exit;
}

$id = gen_id('TN');
$stmt = $conn->prepare(
    "INSERT INTO facility (FacilityId, FacilityName, RoomId, Status, Quantity, Note)
     VALUES (?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param('ssssis', $id, $name, $roomId, $status, $quantity, $note);

if ($stmt->execute()) {
    header('Location: facility.php?ok=added');
} else {
    header('Location: facility.php?error=' . urlencode($stmt->error));
}
exit;
