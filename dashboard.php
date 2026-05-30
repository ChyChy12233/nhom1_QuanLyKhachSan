<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <link rel="stylesheet" href="dashboard.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>

<div class="layout">

<!-- ================= SIDEBAR ================= -->
<div class="sidebar">
  <h2>HMS</h2>

  <a class="active" href="api/home.php" target="mainFrame" data-title="Dashboard">
    <i data-lucide="layout-dashboard"></i> Dashboard
  </a>

  <!-- STAFF -->
  <p class="menu-title">Nhân viên</p>
  <a href="api/customer_list.php" target="mainFrame" data-title="Quản lý khách hàng">
    <i data-lucide="user"></i> Quản lý khách hàng
  </a>
  <a href="api/booking_list.php" target="mainFrame" data-title="Quản lý đặt phòng">
    <i data-lucide="calendar-check"></i> Quản lý đặt phòng
  </a>
  <a href="api/room_usage.php" target="mainFrame" data-title="Quản lý sử dụng phòng">
    <i data-lucide="bed"></i> Quản lý sử dụng phòng
  </a>
  <a href="api/incident_report.php" target="mainFrame" data-title="Gửi báo cáo sự cố">
    <i data-lucide="send"></i> Gửi báo cáo sự cố
  </a>

  <!-- MANAGER -->
  <?php if ($role == 'manager' || $role == 'admin'): ?>
    <p class="menu-title">Quản lý</p>
    <a href="api/staff_list.php" target="mainFrame" data-title="Quản lý nhân sự">
      <i data-lucide="users"></i> Quản lý nhân sự
    </a>
    <a href="api/service_list.php" target="mainFrame" data-title="Quản lý dịch vụ">
      <i data-lucide="coffee"></i> Quản lý dịch vụ
    </a>
    <a href="api/room_list.php" target="mainFrame" data-title="Quản lý loại phòng">
      <i data-lucide="home"></i> Quản lý loại phòng
    </a>
    <a href="api/incident_list.php" target="mainFrame" data-title="Quản lý sự cố">
      <i data-lucide="alert-triangle"></i> Quản lý sự cố
    </a>
    <a href="api/facility.php" target="mainFrame" data-title="Quản lý cơ sở vật chất">
      <i data-lucide="building"></i> Quản lý cơ sở vật chất
    </a>
    <a href="api/invoice_list.php" target="mainFrame" data-title="Quản lý hóa đơn">
      <i data-lucide="file-text"></i> Quản lý hóa đơn
    </a>
    <a href="api/report.php" target="mainFrame" data-title="Báo cáo thống kê">
      <i data-lucide="bar-chart"></i> Báo cáo thống kê
    </a>
  <?php endif; ?>

  <!-- ADMIN -->
  <?php if ($role == 'admin'): ?>
    <div class="admin-box">
      <a class="create-btn" href="create_user.php" target="mainFrame" data-title="Tạo tài khoản">
        <i data-lucide="user-plus"></i> Tạo tài khoản
      </a>
    </div>
  <?php endif; ?>

</div>

<!-- ================= MAIN ================= -->
<div class="main">

  <div class="topbar">
    <h1 id="pageTitle">Dashboard</h1>
    <div class="topbar-user">
      <div class="user-chip">
        <i data-lucide="user-circle-2"></i>
        <span class="user-name"><?= htmlspecialchars($user, ENT_QUOTES, 'UTF-8') ?></span>
        <span class="role-badge"><?= htmlspecialchars($role, ENT_QUOTES, 'UTF-8') ?></span>
      </div>
      <a href="logout.php" class="logout-btn">
        <i data-lucide="log-out"></i>
        Đăng xuất
      </a>
    </div>
  </div>

  <iframe id="mainFrame" name="mainFrame" src="api/home.php"></iframe>

</div>

</div>

<script>
  lucide.createIcons();

  const links = document.querySelectorAll('.sidebar a[data-title]');
  const pageTitle = document.getElementById('pageTitle');

  links.forEach(link => {
    link.addEventListener('click', function () {
      links.forEach(l => l.classList.remove('active'));
      this.classList.add('active');
      pageTitle.textContent = this.getAttribute('data-title');
    });
  });
</script>

</body>
</html>
