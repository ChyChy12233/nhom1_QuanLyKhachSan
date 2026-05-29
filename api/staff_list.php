<?php
$conn = mysqli_connect("localhost","root","","hotel");
$keyword = isset($_GET['keyword']) ? mysqli_real_escape_string($conn, $_GET['keyword']) : "";
$role = isset($_GET['role']) ? $_GET['role'] : "";
/* AUTO STAFF ID */

$getLastStaff = mysqli_query(
    $conn,
    "

    SELECT StaffId

    FROM staff

    ORDER BY StaffId DESC

    LIMIT 1

    "
);

if(mysqli_num_rows($getLastStaff) > 0){

    $lastStaff = mysqli_fetch_assoc($getLastStaff);

    $lastId = $lastStaff['StaffId'];

    /* LẤY PHẦN SỐ */
    $number = (int) preg_replace('/[^0-9]/', '', $lastId);

    $number++;

    $nextStaffId =
    "NV" .
    str_pad($number, 3, "0", STR_PAD_LEFT);

}
else{

    $nextStaffId = "NV001";
}
// QUERY
$sql = "SELECT * FROM staff WHERE 1";

if ($keyword != "") {
    $sql .= " AND (
        LOWER(StaffName) LIKE LOWER('%$keyword%') 
        OR LOWER(Username) LIKE LOWER('%$keyword%') 
        OR PhoneNumber LIKE '%$keyword%'
    )";
}

if ($role != "") {
    $sql .= " AND Role = '$role'";
}

$result = mysqli_query($conn, $sql);

$totalStaff = mysqli_num_rows(
    mysqli_query($conn,"SELECT * FROM staff")
);

$totalAdmin = mysqli_num_rows(
    mysqli_query($conn,"
        SELECT * FROM staff
        WHERE Role='admin'
    ")
);

$totalManager = mysqli_num_rows(
    mysqli_query($conn,"
        SELECT * FROM staff
        WHERE Role='manager'
    ")
);

$totalEmployee = mysqli_num_rows(
    mysqli_query($conn,"
        SELECT * FROM staff
        WHERE Role='staff'
    ")
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Danh sách nhân viên</title>
    <link rel="stylesheet" href="../staff.css">
</head>

<body>

<div class="container">

    <!-- TITLE -->
    <div class="top-header">

        <div>

            <h2>Danh sách nhân viên</h2>

            <p>
                Quản lý thông tin nhân viên khách sạn
            </p>

        </div>

      <button
    class="add-btn"
    onclick="toggleStaffForm()"
>

    + Thêm nhân viên

</button>

    </div>

    <!-- STATS -->
    <div class="stats-grid">

        <div class="stat-card blue">

            <h3>Tổng nhân viên</h3>

            <h1><?= $totalStaff ?></h1>

            <p>Tất cả nhân viên</p>

        </div>

        <div class="stat-card green">

            <h3>Admin</h3>

            <h1><?= $totalAdmin ?></h1>

            <p>Quản trị hệ thống</p>

        </div>

        <div class="stat-card orange">

            <h3>Quản lý</h3>

            <h1><?= $totalManager ?></h1>

            <p>Nhân viên quản lý</p>

        </div>

        <div class="stat-card red">

            <h3>Nhân viên</h3>

            <h1><?= $totalEmployee ?></h1>

            <p>Nhân viên thường</p>

        </div>

    </div>

    <!-- SEARCH -->
    <form method="GET" class="search-box">

        <input
            type="text"
            name="keyword"
            placeholder="Tìm nhân viên..."
            value="<?= $keyword ?>"
        >

        <select name="role">

            <option value="">
                Tất cả vai trò
            </option>

            <option
                value="admin"
                <?= ($role=='admin')?'selected':'' ?>
            >
                Admin
            </option>

            <option
                value="manager"
                <?= ($role=='manager')?'selected':'' ?>
            >
                Manager
            </option>

            <option
                value="staff"
                <?= ($role=='staff')?'selected':'' ?>
            >
                Staff
            </option>

        </select>

        <button type="submit">

            Tìm kiếm

        </button>

    </form>

    <!-- TABLE -->
    <div class="table-wrapper">

        <table>

            <tr>

                <th>ID</th>
                <th>Họ và tên</th>
                <th>SĐT</th>
                <th>Chức vụ</th>
                <th>Action</th>

            </tr>

            <?php if (mysqli_num_rows($result) > 0): ?>

                <?php while($row = mysqli_fetch_assoc($result)): ?>

                <tr>

                    <td><?= $row['StaffId'] ?></td>

                    <td><?= $row['StaffName'] ?></td>

                    <td><?= $row['PhoneNumber'] ?></td>

                    <td><?= $row['Position'] ?></td>

                
                    <td class="action">

                        <a
                            href="edit_staff.php?id=<?= $row['StaffId'] ?>"
                            class="edit"
                        >
                            Sửa
                        </a>

                        <a
                            href="delete_staff.php?id=<?= $row['StaffId'] ?>"
                            class="delete"
                            onclick="return confirm('Bạn chắc chắn muốn xóa?')"
                        >
                            Xóa
                        </a>

                    </td>

                </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>

                    <td colspan="6">

                        Không tìm thấy nhân viên

                    </td>

                </tr>

            <?php endif; ?>

        </table>

    </div>

</div>
<!-- STAFF MODAL -->
<div id="staffModal" class="modal">

    <div class="modal-content staff-modal">

        <!-- HEADER -->
        <div class="modal-header">

            <h3>Thêm nhân viên</h3>

            <span
                class="close-btn"
                onclick="toggleStaffForm()"
            >
                ×
            </span>

        </div>

        <!-- FORM -->
        <form class="form-grid">

            <!-- MÃ NV -->
            <div class="form-group">

                <label>Mã nhân viên</label>

               <input
    type="text"
    name="StaffId"
    value="<?= $nextStaffId ?>"
    readonly
>

            </div>

            <!-- HỌ TÊN -->
            <div class="form-group">

                <label>Họ và tên</label>

                <input type="text">

            </div>

            <!-- SDT -->
            <div class="form-group">

                <label>Số điện thoại</label>

                <input type="text">

            </div>

            <!-- EMAIL -->
            <div class="form-group">

                <label>Email</label>

                <input type="email">

            </div>

            <!-- CCCD -->
            <div class="form-group">

                <label>CCCD</label>

                <input type="text">

            </div>

            <!-- NGÀY SINH -->
            <div class="form-group">

                <label>Ngày sinh</label>

                <input type="date">

            </div>

            <!-- GIỚI TÍNH -->
            <div class="form-group">

                <label>Giới tính</label>

                <select>

                    <option>Nam</option>

                    <option>Nữ</option>

                </select>

            </div>

            <!-- CHỨC VỤ -->
            <div class="form-group">

                <label>Chức vụ</label>

                <select>

                    <option>Quản lý</option>

                    <option>Nhân viên</option>

                </select>

            </div>

            <!-- BUTTON -->
            <button
                type="submit"
                class="submit-btn full"
            >

                Thêm nhân viên

            </button>

        </form>

    </div>

</div>
<script>

function toggleStaffForm(){

    document
        .getElementById("staffModal")
        .classList
        .toggle("show");
}

</script>
</body>
</html>