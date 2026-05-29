<?php
$conn = mysqli_connect("localhost","root","","hotel");

/* ================= QUERY ================= */

$sql = "

SELECT 

    b.*,

    c.CustomerName,

    c.StayCount,

    c.TotalSpent,

    r.RoomNumber,

    rt.RoomTypeName

FROM booking b

JOIN customer c 
ON b.CustomerId = c.CustomerId

JOIN room r 
ON b.RoomId = r.RoomId

JOIN room_type rt
ON r.RoomTypeId = rt.RoomTypeId

ORDER BY b.BookingId DESC

";

$result = mysqli_query($conn, $sql);
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

                <input
                    type="text"
                    id="detailRoomType"
                    readonly
                >

            </div>

            <div class="form-group">

                <label>Ngày nhận</label>

                <input
                    type="text"
                    id="detailCheckIn"
                    readonly
                >

            </div>

            <div class="form-group">

                <label>Ngày trả</label>

                <input
                    type="text"
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

                <input
                    type="text"
                    id="detailStatus"
                    readonly
                >

            </div>

        </div>

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

</body>
</html>