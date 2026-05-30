<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['manager', 'admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: facility.php');
    exit;
}

$id       = trim($_POST['FacilityId']   ?? '');
$name     = trim($_POST['FacilityName'] ?? '');
$roomId   = trim($_POST['RoomId']       ?? '') ?: null;
$quantity = (int)($_POST['Quantity']    ?? 1);
$status   = $_POST['Status'] ?? 'Hoạt động';
$note     = trim($_POST['Note'] ?? '') ?: null;

if ($id === '' || $name === '') {
    header('Location: facility.php?error=missing_fields');
    exit;
}

$stmt = $conn->prepare(
    "UPDATE facility SET FacilityName=?, RoomId=?, Status=?, Quantity=?, Note=? WHERE FacilityId=?"
);
$stmt->bind_param('sssiss', $name, $roomId, $status, $quantity, $note, $id);

if ($stmt->execute()) {
    header('Location: facility.php?ok=updated');
} else {
    header('Location: facility.php?error=' . urlencode($stmt->error));
}
exit;
