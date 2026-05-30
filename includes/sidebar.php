<?php
// includes/sidebar.php
// Extracted from dashboard.php. Used when pages are rendered standalone (Step 6 refactor).
// Requires $_SESSION['role'] to be set (set by login.php).
$_role = $_SESSION['role'] ?? '';
?>
<div class="sidebar">
  <h2>HMS</h2>

  <a href="/nhom1_QuanLyKhachSan/api/home.php" data-title="Dashboard">
    <i data-lucide="layout-dashboard"></i> Dashboard
  </a>

  <!-- STAFF -->
  <p class="menu-title">Nhân viên</p>
  <a href="/nhom1_QuanLyKhachSan/api/customer_list.php" data-title="Quản lý khách hàng">
    <i data-lucide="user"></i> Quản lý khách hàng
  </a>
  <a href="/nhom1_QuanLyKhachSan/api/booking_list.php" data-title="Quản lý đặt phòng">
    <i data-lucide="calendar-check"></i> Quản lý đặt phòng
  </a>
  <a href="/nhom1_QuanLyKhachSan/api/invoice_list.php" data-title="Quản lý hóa đơn">
    <i data-lucide="file-text"></i> Quản lý hóa đơn
  </a>
  <a href="/nhom1_QuanLyKhachSan/api/room_usage.php" data-title="Quản lý sử dụng phòng">
    <i data-lucide="bed"></i> Quản lý sử dụng phòng
  </a>
  <a href="/nhom1_QuanLyKhachSan/api/incident_report.php" data-title="Gửi báo cáo sự cố">
    <i data-lucide="send"></i> Gửi báo cáo sự cố
  </a>

  <!-- MANAGER -->
  <?php if ($_role === 'manager' || $_role === 'admin'): ?>
    <p class="menu-title">Quản lý</p>
    <a href="/nhom1_QuanLyKhachSan/api/staff_list.php" data-title="Quản lý nhân sự">
      <i data-lucide="users"></i> Quản lý nhân sự
    </a>
    <a href="/nhom1_QuanLyKhachSan/api/service_list.php" data-title="Quản lý dịch vụ">
      <i data-lucide="coffee"></i> Quản lý dịch vụ
    </a>
    <a href="/nhom1_QuanLyKhachSan/api/room_list.php" data-title="Quản lý loại phòng">
      <i data-lucide="home"></i> Quản lý loại phòng
    </a>
    <a href="/nhom1_QuanLyKhachSan/api/incident_list.php" data-title="Quản lý sự cố">
      <i data-lucide="alert-triangle"></i> Quản lý sự cố
    </a>
    <a href="/nhom1_QuanLyKhachSan/api/facility.php" data-title="Quản lý cơ sở vật chất">
      <i data-lucide="building"></i> Quản lý cơ sở vật chất
    </a>
    <a href="/nhom1_QuanLyKhachSan/api/report.php" data-title="Báo cáo thống kê">
      <i data-lucide="bar-chart"></i> Báo cáo thống kê
    </a>
  <?php endif; ?>

  <!-- ADMIN -->
  <?php if ($_role === 'admin'): ?>
    <div class="admin-box">
      <a class="create-btn" href="/nhom1_QuanLyKhachSan/create_user.php" data-title="Tạo tài khoản">
        <i data-lucide="user-plus"></i> Tạo tài khoản
      </a>
    </div>
  <?php endif; ?>
</div>
