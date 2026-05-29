<?php
$conn = mysqli_connect("localhost","root","","hotel");

/* ================= QUERY ================= */

$keyword = isset($_GET['keyword'])
? mysqli_real_escape_string($conn,$_GET['keyword'])
: "";

$roomType = isset($_GET['roomType'])
? $_GET['roomType']
: "";

$sql = "

SELECT 

    b.*,

    c.CustomerName,

    r.RoomNumber,

    rt.RoomTypeName

FROM booking b

JOIN customer c
ON b.CustomerId = c.CustomerId

JOIN room r
ON b.RoomId = r.RoomId

JOIN room_type rt
ON r.RoomTypeId = rt.RoomTypeId

WHERE 1

";

if($keyword != ""){

    $sql .= "

    AND(

        b.BookingId LIKE '%$keyword%'

        OR

        c.CustomerName LIKE '%$keyword%'

    )

    ";
}

if($roomType != ""){

    $sql .= "

    AND rt.RoomTypeName = '$roomType'

    ";
}

$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Quản lý đặt phòng</title>

    <link rel="stylesheet" href="../booking.css">

</head>

<body>

<div class="booking-container">

    <h2>Danh sách đặt phòng</h2>
<!-- TOP BAR -->
<div class="top-bar">

    <!-- SEARCH -->
    <form method="GET" class="search-box">

        <input
            type="text"
            name="keyword"
            placeholder="Tìm mã đặt phòng, khách hàng..."
            value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>"
        >

        <!-- FILTER -->
        <select name="roomType">

            <option value="">
                Tất cả loại phòng
            </option>

            <option value="Standard">
                Standard
            </option>

            <option value="Deluxe">
                Deluxe
            </option>

            <option value="Suite">
                Suite
            </option>

        </select>

        <button type="submit">
            Tìm kiếm
        </button>

    </form>

</div>
<button
    class="add-booking-btn"
    onclick="openAddBookingModal()"
>
    + Thêm phiếu đặt phòng
</button>
    <!-- TABLE -->
    <table>

        <tr>

            <th>Mã đặt phòng</th>

            <th>Phòng</th>

            <th>Loại phòng</th>

            <th>Khách hàng</th>

            <th>Ngày nhận</th>

            <th>Ngày trả</th>

            <th>Trạng thái</th>
            <th>Action</th>

        </tr>

        <?php while($row = mysqli_fetch_assoc($result)): ?>

        <tr
            onclick="openBookingDetail(
                '<?= $row['BookingId'] ?>',
                '<?= $row['CustomerName'] ?>',
                '<?= $row['RoomNumber'] ?>',
                '<?= $row['RoomTypeName'] ?>',
                '<?= $row['CheckInDate'] ?>',
                '<?= $row['CheckOutDate'] ?>',
                '<?= $row['SoNguoi'] ?>',
                '<?= number_format($row['DonGia']) ?>đ',
                '<?= $row['TrangThai'] ?>'
            )"
            style="cursor:pointer;"
        >

            <!-- BOOKING ID -->
            <td>

                <?= $row['BookingId'] ?>

            </td>

            <!-- ROOM -->
            <td>

                <?= $row['RoomNumber'] ?>

            </td>

            <!-- ROOM TYPE -->
            <td>

                <?= $row['RoomTypeName'] ?>

            </td>

            <!-- CUSTOMER -->
            <td>

                <?= $row['CustomerName'] ?>

            </td>

            <!-- CHECK IN -->
            <td>

                <?= $row['CheckInDate'] ?>

            </td>

            <!-- CHECK OUT -->
            <td>

                <?= $row['CheckOutDate'] ?>

            </td>

            <!-- STATUS -->
            <td>

                <?php

                if($row['TrangThai'] == "đã thanh toán"){

                    echo "
                    <span class='paid'>
                        Đã thanh toán
                    </span>
                    ";
                }
                else{

                    echo "
                    <span class='unpaid'>
                        Chưa thanh toán
                    </span>
                    ";
                }

                ?>

            </td>
            <td>

<button
    class="delete-btn"
    onclick="event.stopPropagation(); openDeleteModal();"
>
    Xóa
</button>

</td>

        </tr>

        <?php endwhile; ?>

    </table>

</div>

<!-- DETAIL MODAL -->
<div id="bookingModal" class="modal">

    <div class="modal-content booking-modal">

        <!-- HEADER -->
        <div class="modal-header">

            <h3>Chi tiết phiếu đặt phòng</h3>

            <span
                class="close-btn"
                onclick="closeBookingDetail()"
            >
                ×
            </span>

        </div>
      


        <!-- GRID -->
        <div class="booking-grid">

            <div class="form-group">

                <label>Mã đặt phòng</label>

                <input
                    type="text"
                    id="detailBookingId"
                    readonly
                >

            </div>

            <div class="form-group">

                <label>Khách hàng</label>

                <input
                    type="text"
                    id="detailCustomer"
                    readonly
                >

            </div>

            <div class="form-group">

                <label>Phòng</label>

                <input
                    type="text"
                    id="detailRoom"
                    readonly
                >

            </div>

            <div class="form-group">

                <label>Loại phòng</label>

          <select id="detailRoomType" disabled>

    <option>Standard</option>

    <option>Deluxe</option>

    <option>Suite</option>

    <option>VIP</option>

</select>

            </div>

            <div class="form-group">

                <label>Ngày nhận</label>

                <input
                    type="date"
                    id="detailCheckIn"
                    readonly
                >

            </div>

            <div class="form-group">

                <label>Ngày trả</label>
<input
    type="date"
    id="detailCheckOut"
    readonly
>

            </div>

            <div class="form-group">

                <label>Số người</label>

                <input
                    type="text"
                    id="detailPeople"
                    readonly
                >

            </div>

            <div class="form-group">

                <label>Đơn giá</label>

                <input
                    type="text"
                    id="detailPrice"
                    readonly
                >

            </div>

            <div class="form-group full">

                <label>Trạng thái</label>

              <select id="detailStatus" disabled>

    <option>Chưa thanh toán</option>

    <option>Đã thanh toán</option>
    <option>Đã hủy</option>

</select>

            </div>
            </div>
        <!-- ACTION -->
        <div class="booking-actions">

            <button
                type="button"
                class="edit-btn"
                onclick="enableEditBooking()"
            >
                Chỉnh sửa
            </button>

            <button
                type="button"
                class="save-btn"
                id="saveBookingBtn"
                style="display:none;"
            >
                Lưu thay đổi
            </button>

        </div>
    </div>

</div>
 
<!-- DELETE MODAL -->
<div id="deleteModal" class="modal">

    <div class="delete-modal">

        <h3>Xác nhận xóa</h3>

        <p>
            Bạn chắc chắn muốn xóa phiếu đặt phòng này?
        </p>

        <div class="delete-actions">

            <button
                class="cancel-btn"
                onclick="closeDeleteModal()"
            >
                Hủy
            </button>

            <button
                class="confirm-delete-btn"
            >
                Xóa phiếu
            </button>

        </div>
    </div>

</div>
<!-- ADD BOOKING MODAL -->
<div id="addBookingModal" class="modal">

    <div class="modal-content booking-modal">

        <!-- HEADER -->
        <div class="modal-header">

            <h3>Thêm phiếu đặt phòng</h3>

            <span
                class="close-btn"
                onclick="closeAddBookingModal()"
            >
                ×
            </span>

        </div>

        <!-- FORM -->
        <form class="booking-grid">

            <!-- MÃ PDP -->
            <div class="form-group">

                <label>Mã phiếu đặt phòng</label>

                <input
                    type="text"
                    value="PDP004"
                    readonly
                >

            </div>

            <!-- KHÁCH HÀNG -->
            <div class="form-group">

                <label>Khách hàng</label>

                <select>

                    <option>KH001 - Nguyễn Văn A</option>

                    <option>KH002 - Trần Minh Tú</option>

                    <option>KH003 - Lê Hoàng Anh</option>

                </select>

            </div>

            <!-- PHÒNG -->
            <div class="form-group">

                <label>Phòng</label>

                <select>

                    <option>P101</option>

                    <option>P202</option>

                    <option>P301</option>

                </select>

            </div>

            <!-- LOẠI PHÒNG -->
            <div class="form-group">

                <label>Loại phòng</label>

                <select>

                    <option>Standard</option>

                    <option>Deluxe</option>

                    <option>Suite</option>

                </select>

            </div>

            <!-- NGÀY NHẬN -->
            <div class="form-group">

                <label>Ngày nhận</label>

                <input type="date">

            </div>

            <!-- NGÀY TRẢ -->
            <div class="form-group">

                <label>Ngày trả</label>

                <input type="date">

            </div>

            <!-- SỐ NGƯỜI -->
            <div class="form-group">

                <label>Số người</label>

                <input
                    type="number"
                    value="1"
                >

            </div>

            <!-- ĐƠN GIÁ -->
            <div class="form-group">

                <label>Đơn giá</label>

                <input
                    type="number"
                    placeholder="Nhập đơn giá"
                >

            </div>

            <!-- TRẠNG THÁI -->
            <div class="form-group full">

                <label>Trạng thái</label>

                <select>

                    <option>chưa thanh toán</option>

                    <option>đã thanh toán</option>

                </select>

            </div>

            <!-- BUTTON -->
            <div class="booking-actions full">

                <button
                    type="submit"
                    class="save-btn"
                >
                    Thêm phiếu đặt phòng
                </button>

            </div>

        </form>

    </div>

</div>
<script>

function openBookingDetail(
    bookingId,
    customer,
    room,
    roomType,
    checkIn,
    checkOut,
    people,
    price,
    status
){

    document.getElementById("bookingModal")
        .classList.add("show");

    document.getElementById("detailBookingId")
        .value = bookingId;

    document.getElementById("detailCustomer")
        .value = customer;

    document.getElementById("detailRoom")
        .value = room;

    document.getElementById("detailRoomType")
        .value = roomType;

    document.getElementById("detailCheckIn")
        .value = checkIn;

    document.getElementById("detailCheckOut")
        .value = checkOut;

    document.getElementById("detailPeople")
        .value = people;

    document.getElementById("detailPrice")
        .value = price;

    document.getElementById("detailStatus")
        .value = status;
}

function closeBookingDetail(){

    document.getElementById("bookingModal")
        .classList.remove("show");
}

</script>
<script>
function enableEditBooking(){

    document.getElementById("detailCheckIn")
        .removeAttribute("readonly");

    document.getElementById("detailCheckOut")
        .removeAttribute("readonly");

    document.getElementById("detailPeople")
        .removeAttribute("readonly");

    document.getElementById("detailPrice")
        .removeAttribute("readonly");

    document.getElementById("detailRoomType")
        .disabled = false;

    document.getElementById("detailStatus")
        .disabled = false;

    document.getElementById("saveBookingBtn")
        .style.display = "inline-block";
}
</script>
<script>
function openDeleteModal(){

    // Ẩn popup chi tiết
    document
        .getElementById("bookingModal")
        .classList.remove("show");

    // Hiện popup xóa
    document
        .getElementById("deleteModal")
        .classList.add("show");
}
</script>
function openAddBookingModal(){

    document
        .getElementById("addBookingModal")
        .classList.add("show");
}
<script>

function openBookingDetail(
    bookingId,
    customer,
    room,
    roomType,
    checkIn,
    checkOut,
    people,
    price,
    status
){

    document.getElementById("bookingModal")
        .classList.add("show");

    document.getElementById("detailBookingId")
        .value = bookingId;

    document.getElementById("detailCustomer")
        .value = customer;

    document.getElementById("detailRoom")
        .value = room;

    document.getElementById("detailRoomType")
        .value = roomType;

    document.getElementById("detailCheckIn")
        .value = checkIn;

    document.getElementById("detailCheckOut")
        .value = checkOut;

    document.getElementById("detailPeople")
        .value = people;

    document.getElementById("detailPrice")
        .value = price;

    document.getElementById("detailStatus")
        .value = status;
}

/* CLOSE DETAIL */
function closeBookingDetail(){

    document
        .getElementById("bookingModal")
        .classList.remove("show");
}

/* EDIT */
function enableEditBooking(){

    document.getElementById("detailCheckIn")
        .removeAttribute("readonly");

    document.getElementById("detailCheckOut")
        .removeAttribute("readonly");

    document.getElementById("detailPeople")
        .removeAttribute("readonly");

    document.getElementById("detailPrice")
        .removeAttribute("readonly");

    document.getElementById("detailRoomType")
        .disabled = false;

    document.getElementById("detailStatus")
        .disabled = false;

    document.getElementById("saveBookingBtn")
        .style.display = "inline-block";
}

/* DELETE */
function openDeleteModal(){

    document
        .getElementById("bookingModal")
        .classList.remove("show");

    document
        .getElementById("deleteModal")
        .classList.add("show");
}

function closeDeleteModal(){

    document
        .getElementById("deleteModal")
        .classList.remove("show");
}

/* ADD BOOKING */
function openAddBookingModal(){

    document
        .getElementById("addBookingModal")
        .classList.add("show");
}

function closeAddBookingModal(){

    document
        .getElementById("addBookingModal")
        .classList.remove("show");
}

</script>
</body>
</html>