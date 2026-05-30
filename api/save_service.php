<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['manager', 'admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: service_list.php');
    exit;
}

$name  = trim($_POST['ServiceName'] ?? '');
$price = trim($_POST['Price'] ?? '');
$unit  = trim($_POST['Unit'] ?? 'lần');
$note  = trim($_POST['Note'] ?? '') ?: null;

if ($name === '' || $price === '') {
    header('Location: service_list.php?error=missing_fields');
    exit;
}

$id = gen_id('SV');

$stmt = $conn->prepare(
    "INSERT INTO service (ServiceId, ServiceName, Price, Unit, Note) VALUES (?, ?, ?, ?, ?)"
);
$stmt->bind_param('ssdss', $id, $name, $price, $unit, $note);

if ($stmt->execute()) {
    header('Location: service_list.php?ok=added');
} else {
    header('Location: service_list.php?error=' . urlencode($stmt->error));
}
exit;
