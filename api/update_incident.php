<?php
// Đánh dấu sự cố đã xử lý xong.
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['manager', 'admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: incident_list.php');
    exit;
}

$id = trim($_POST['id'] ?? '');
if ($id === '') {
    header('Location: incident_list.php?error=missing_id');
    exit;
}

$resolvedAt = date('Y-m-d H:i:s');
$stmt = $conn->prepare(
    "UPDATE incident SET Status='Đã xử lý', ResolvedAt=? WHERE IncidentId=?"
);
$stmt->bind_param('ss', $resolvedAt, $id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    header('Location: incident_list.php?ok=resolved');
} else {
    header('Location: incident_list.php?error=' . urlencode('Không tìm thấy sự cố hoặc đã xử lý rồi'));
}
exit;
