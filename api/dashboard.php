<?php
session_start();

if (!isset($_SESSION['user'])) {

    header("Location: index.html");

    exit();
}

/* SESSION */
$user = $_SESSION['user'];

/* CONNECT DATABASE */
$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "hotel"
);

/* QUERY STAFF */
$sql = "

SELECT *

FROM staff

WHERE Username = '$user'

";

$result = mysqli_query($conn,$sql);

$staff = mysqli_fetch_assoc($result);

/* ROLE */
$role = strtolower(
    trim($staff['Role'])
);
?>
<?php
if(isset($_POST['change_password'])){

    $oldPassword =
        $_POST['old_password'];

    $newPassword =
        $_POST['new_password'];

    $confirmPassword =
        $_POST['confirm_password'];

    /* CHECK OLD PASSWORD */

    if(
    !password_verify(
        $oldPassword,
        $staff['Password']
    )
){

        echo "

        <script>

            alert('Mật khẩu cũ không đúng');

        </script>

        ";
    }

    /* CHECK CONFIRM */

    else if($newPassword != $confirmPassword){

        echo "

        <script>

            alert('Xác nhận mật khẩu không khớp');

        </script>

        ";
    }

    else{

    $newHash = password_hash(
        $newPassword,
        PASSWORD_DEFAULT
    );

    $update = "

    UPDATE staff

    SET Password = '$newHash'

    WHERE StaffId = '".$staff['StaffId']."'

    ";

    mysqli_query($conn,$update);

    echo "

    <script>

        alert('Đổi mật khẩu thành công');

        window.location.href='dashboard.php';

    </script>

    ";
  }}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <link rel="stylesheet" href="../dashboard.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>

<div class="layout">

<!-- ================= SIDEBAR ================= -->
<div class="sidebar">
  <h2>HMS</h2>

  <a class="active"><i data-lucide="layout-dashboard"></i> Dashboard</a>

  <!-- STAFF -->
  <p class="menu-title">Nhân viên</p>
  <a href="customer_list.php"><i data-lucide="user"></i> Quản lý khách hàng</a>

  <a href="room_list.php">
    <i data-lucide="bed"></i> Quản lý phòng
  </a>

  <a href="booking_checkin.php">
    <i data-lucide="calendar-plus"></i> Quản lý đặt phòng
  </a>

  <!-- 🔥 FIX: thêm link -->
  <a href="invoice_list.php">
    <i data-lucide="file-text"></i> Quản lý hóa đơn
  </a>

  <a href="room_usage.php">
    <i data-lucide="bed"></i> Quản lý sử dụng phòng
  </a>

  <a href="incident_report.php">
    <i data-lucide="send"></i> Gửi báo cáo sự cố
  </a>

  <!-- MANAGER -->
  <?php if ($role == 'manager' || $role == 'admin'): ?>
    <p class="menu-title">Quản lý</p>

    <a href="staff_list.php">
      <i data-lucide="users"></i> Quản lý nhân sự
    </a>

    <a href="service_list.php">
      <i data-lucide="coffee"></i> Quản lý dịch vụ
    </a>

    <a href="room_type.php">
      <i data-lucide="home"></i> Quản lý loại phòng
    </a>

    <a href="incident_list.php">
      <i data-lucide="alert-triangle"></i> Quản lý sự cố
    </a>

    <a href="facility_list.php">
      <i data-lucide="building"></i> Quản lý cơ sở vật chất
    </a>

    <a href="facility.php">
      <i data-lucide="box"></i> Quản lý kho tiện nghi
    </a>

    <!-- 🔥 FIX: thêm link -->
    <a href="revenue_report.php">
      <i data-lucide="bar-chart"></i> Báo cáo thống kê
    </a>

  <?php endif; ?>

  <!-- ADMIN -->
  <?php if ($role == 'admin'): ?>
    <div class="admin-box">
      <a class="create-btn" href="create_user.php">
        <i data-lucide="user-plus"></i> Tạo tài khoản
      </a>
    </div>
  <?php endif; ?>

</div>

<!-- ================= MAIN ================= -->
<div class="main">
  <!-- PROFILE MODAL -->
<div id="profileModal" class="modal">
    <div class="profile-modal">

        <div class="profile-top">

            <div class="profile-avatar">

                <i data-lucide="user"></i>

            </div>

            <div class="profile-info">

                <h2>
                    <?php echo $user; ?>
                </h2>

                <p>
                    Quản trị viên -
                    NV001
                </p>

            </div>

            <span class="status-badge">

                Đang hoạt động

            </span>

        </div>

        <!-- BODY -->
        <div class="profile-body">

            <h3>
                Thông tin nhân viên
            </h3>

            <div class="profile-grid">

                <div class="form-group">

                    <label>Mã Nhân Viên</label>

                    <input
                        type="text"
                        value="<?php echo $staff['StaffId']; ?>"
                        readonly
                    >

                </div>

                <div class="form-group">

                    <label>Tên Nhân Viên</label>

                    <input
                        type="text"
                        value="<?php echo $staff['StaffName']; ?>"
                        readonly
                    >

                </div>

                <div class="form-group">

                    <label>Số điện thoại</label>

                    <input
                        type="text"
                        value="<?php echo $staff['PhoneNumber']; ?>"
                        readonly
                    >

                </div>

                <div class="form-group">

                    <label>Email</label>

                    <input
                        type="text"
                        value="<?php echo $staff['Email']; ?>"
                        readonly
                    >

                </div>

                <div class="form-group">

                    <label>CCCD</label>

                    <input
                        type="text"
                        value="<?php echo $staff['CCCD']; ?>"
                        readonly
                    >

                </div>

                <div class="form-group">

                    <label>Ngày sinh</label>

                    <input
                        type="text"
                       value="<?php echo $staff['Birthday']; ?>"
                        readonly
                    >

                </div>

                <div class="form-group">

                    <label>Giới tính</label>

                    <input
                        type="text"
                        value="<?php echo $staff['Gender']; ?>"
                        readonly
                    >

                </div>

                <div class="form-group">

                    <label>Chức vụ</label>

                    <input
                        type="text"
                        value="<?php echo $staff['Position']; ?>"
                        readonly
                    >

                </div>

                <div class="form-group">

                    <label>Tên tài khoản</label>

                    <input
                        type="text"
                        value="<?php echo $staff['Username']; ?>"
                        readonly
                    >

                </div>

                <div class="form-group">

                    <label>Mật khẩu</label>

                    <input
                        type="password"
                        value="<?php echo $staff['Password']; ?>"
                        readonly
                    >

                </div>

            </div>

        </div>
        <!-- CHANGE PASSWORD MODAL -->
        <!-- ACTION -->
        <div class="profile-actions">

           <button
    class="change-pass-btn"
    onclick="openChangePasswordModal()"
>

    Đổi mật khẩu

</button>

            <a
                href="logout.php"
                class="logout-btn"
            >

                Đăng xuất

            </a>

        </div>

    </div>

</div>
<!-- CHANGE PASSWORD MODAL -->
<div id="changePasswordModal" class="modal">

    <div class="change-password-modal">

        <div class="modal-header">

            <h3>Đổi mật khẩu</h3>

            <span
                class="close-btn"
                onclick="closeChangePasswordModal()"
            >
                ×
            </span>

        </div>

        <form
            method="POST"
            class="change-password-form"
        >

            <div class="form-group">

                <label>Mật khẩu cũ</label>

                <input
                    type="password"
                    name="old_password"
                    required
                >

            </div>

            <div class="form-group">

                <label>Mật khẩu mới</label>

                <input
                    type="password"
                    name="new_password"
                    required
                >

            </div>

            <div class="form-group">

                <label>Xác nhận mật khẩu</label>

                <input
                    type="password"
                    name="confirm_password"
                    required
                >

            </div>

            <button
                type="submit"
                name="change_password"
                class="save-password-btn"
            >

                Lưu mật khẩu

            </button>

        </form>

    </div>

</div>
  <div class="topbar">
    <h1>Dashboard</h1>

    <!-- 👇 hiển thị role đã chuẩn hóa -->
    <div
    class="admin-profile"
    onclick="openProfileModal()"
>

    <div class="admin-info">

        <span class="admin-name">
            <?php echo $user; ?>
        </span>

        <span class="admin-role">
            <?php echo strtoupper($role); ?>
        </span>

    </div>

    <div class="admin-avatar">

        <i data-lucide="user"></i>

    </div>

</div>
  </div>

  <p>Chào mừng bạn đến hệ thống quản lý khách sạn</p>

  <!-- CARDS -->
  <div class="cards">

    <div class="card blue">

        <div class="card-icon">
            <i data-lucide="bed"></i>
        </div>

        <h1>120</h1>

        <p>Tổng số phòng</p>

    </div>

    <div class="card green">

        <div class="card-icon">
            <i data-lucide="coffee"></i>
        </div>

        <h1>45</h1>

        <p>Tổng dịch vụ</p>

    </div>

    <div class="card orange">

        <div class="card-icon">
            <i data-lucide="home"></i>
        </div>

        <h1>8</h1>

        <p>Tổng loại phòng</p>

    </div>

    <div class="card purple">

        <div class="card-icon">
            <i data-lucide="users"></i>
        </div>

        <h1>67</h1>

        <p>Tổng nhân viên</p>

    </div>

    <div class="card red">

        <div class="card-icon">
            <i data-lucide="shield"></i>
        </div>

        <h1>12</h1>

        <p>Tổng quản lý</p>

    </div>

</div>
<div class="dashboard-grid">

    <!-- TABLE -->
    <div class="table-box">

        <div class="box-header">

            <h3>Hóa đơn gần đây</h3>

            <a href="#">Xem tất cả</a>

        </div>

        <table>

            <tr>

                <th>Mã hóa đơn</th>

                <th>Khách hàng</th>

                <th>Nhân viên</th>

                <th>Trạng thái</th>

                <th>Thành tiền</th>

            </tr>

            <tr>

                <td>HD001</td>

                <td>KH001</td>

                <td>NV001</td>

                <td>
                    <span class="success">
                        Đã thanh toán
                    </span>
                </td>

                <td>5.500.000đ</td>

            </tr>

        </table>

    </div>
    <div class="status-box">

    <h3>Trạng thái phòng</h3>

    <div class="status-item">

        <span>Đang sử dụng</span>

        <span>24</span>

    </div>

    <div class="progress">

        <div class="blue-bar"></div>

    </div>

</div>
<div class="quick-grid">

    <div class="quick-card">

        <i data-lucide="bed"></i>

        <p>Quản lý phòng</p>

    </div>

    <div class="quick-card">

        <i data-lucide="file-text"></i>

        <p>Quản lý hóa đơn</p>

    </div>

</div>

</div>

</div>

<script>
  lucide.createIcons();
</script>
<script>

function openProfileModal(){

    document
        .getElementById("profileModal")
        .classList.add("show");
}

function closeProfileModal(){

    document
        .getElementById("profileModal")
        .classList.remove("show");
}

window.onclick = function(event){

    const modal =
        document.getElementById("profileModal");

    if(event.target == modal){

        closeProfileModal();
    }
}

</script>
<script>

function openChangePasswordModal(){

    document
        .getElementById("changePasswordModal")
        .classList.add("show");
}

function closeChangePasswordModal(){

    document
        .getElementById("changePasswordModal")
        .classList.remove("show");
}

</script>
</body>
</html>