<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['manager', 'admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: service_list.php');
    exit;
}

$id    = trim($_POST['ServiceId'] ?? '');
$name  = trim($_POST['ServiceName'] ?? '');
$price = trim($_POST['Price'] ?? '');
$unit  = trim($_POST['Unit'] ?? 'lần');
$note  = trim($_POST['Note'] ?? '') ?: null;

if ($id === '' || $name === '' || $price === '') {
    header('Location: service_list.php?error=missing_fields');
    exit;
}

$stmt = $conn->prepare(
    "UPDATE service SET ServiceName=?, Price=?, Unit=?, Note=? WHERE ServiceId=?"
);
$stmt->bind_param('sdsss', $name, $price, $unit, $note, $id);

if ($stmt->execute()) {
    header('Location: service_list.php?ok=updated');
} else {
    header('Location: service_list.php?error=' . urlencode($stmt->error));
}
exit;
