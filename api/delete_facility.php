<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['manager', 'admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: facility.php');
    exit;
}

$id = trim($_POST['id'] ?? '');
if ($id === '') {
    header('Location: facility.php?error=missing_id');
    exit;
}

$stmt = $conn->prepare("DELETE FROM facility WHERE FacilityId=?");
$stmt->bind_param('s', $id);

if ($stmt->execute()) {
    header('Location: facility.php?ok=deleted');
} else {
    header('Location: facility.php?error=' . urlencode($stmt->error));
}
exit;
