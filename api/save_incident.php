<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: incident_report.php');
    exit;
}

$roomId     = trim($_POST['RoomId']     ?? '');
$reportedBy = trim($_POST['ReportedBy'] ?? '') ?: null;
$title      = trim($_POST['Title']      ?? '');
$severity   = $_POST['Severity']    ?? 'Trung bình';
$desc       = trim($_POST['Description'] ?? '') ?: null;

if ($roomId === '' || $title === '') {
    header('Location: incident_report.php?error=missing_fields');
    exit;
}

$id = gen_id('SC');

$stmt = $conn->prepare(
    "INSERT INTO incident (IncidentId, RoomId, ReportedBy, Title, Description, Severity, Status)
     VALUES (?, ?, ?, ?, ?, ?, 'Mới')"
);
$stmt->bind_param('ssssss', $id, $roomId, $reportedBy, $title, $desc, $severity);

if ($stmt->execute()) {
    header('Location: incident_report.php?ok=sent');
} else {
    header('Location: incident_report.php?error=' . urlencode($stmt->error));
}
exit;
