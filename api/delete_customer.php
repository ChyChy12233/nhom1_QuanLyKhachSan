<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: customer_list.php');
    exit;
}

$id = trim($_POST['id'] ?? '');
if ($id === '') {
    header('Location: customer_list.php?error=missing_id');
    exit;
}

$stmt = $conn->prepare("DELETE FROM customer WHERE CustomerId=?");
$stmt->bind_param('s', $id);

if ($stmt->execute()) {
    header('Location: customer_list.php?ok=deleted');
} else {
    header('Location: customer_list.php?error=' . urlencode($stmt->error));
}
exit;
