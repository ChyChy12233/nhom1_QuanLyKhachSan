<?php
// includes/auth.php
// Requires: must be included BEFORE any output.
// Sets session, guards unauthenticated requests, provides require_role().
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user'])) {
    header('Location: /nhom1_QuanLyKhachSan/index.php');
    exit;
}

/**
 * Abort with 403 if the current user's role is not in $roles.
 * Outputs a minimal inline message (safe inside iframe).
 */
function require_role(array $roles): void {
    if (!in_array($_SESSION['role'] ?? '', $roles, true)) {
        http_response_code(403);
        echo '<div style="padding:32px;font-family:sans-serif;color:#ef4444;">'
           . '<strong>403 — Bạn không có quyền truy cập trang này.</strong>'
           . '</div>';
        exit;
    }
}
