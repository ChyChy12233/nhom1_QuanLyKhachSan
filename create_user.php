<?php
require_once __DIR__ . '/includes/auth.php';
require_role(['admin']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tạo tài khoản</title>

    <!-- CSS -->
    <link rel="stylesheet" href="create_user.css">

    <!-- ICON -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>

<div class="form-container">

    <h2>Tạo tài khoản mới</h2>

    <form action="save_user.php" method="POST">

        <div class="input-group">
            <i data-lucide="user"></i>
            <input type="text" name="username" placeholder="Username" required>
        </div>

        <div class="input-group">
            <i data-lucide="lock"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <div class="input-group">
            <i data-lucide="users"></i>
            <select name="role">
                <option value="manager">Manager</option>
                <option value="staff">Staff</option>
            </select>
        </div>

        <button type="submit">Tạo tài khoản</button>

    </form>

</div>

<!-- KÍCH HOẠT ICON -->
<script>
  lucide.createIcons();
</script>

</body>
</html>
