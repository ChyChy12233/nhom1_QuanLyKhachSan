<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>Đặt phòng</title>

    <link rel="stylesheet" href="../booking.css">

</head>

<body>
<?php
$conn = mysqli_connect("localhost","root","","hotel");

/* =========================
   GET DATA
========================= */

$checkIn  = isset($_GET['CheckInDate']) ? $_GET['CheckInDate'] : '';
$checkOut = isset($_GET['CheckOutDate']) ? $_GET['CheckOutDate'] : '';

$selectedCustomerId =
isset($_GET['CustomerId'])
? $_GET['CustomerId']
: '';

$selectedRoomType =
isset($_GET['RoomTypeId'])
? $_GET['RoomTypeId']
: '';

/* =========================
   LOAD CUSTOMERS
========================= */

$customers =
mysqli_query($conn,"
    SELECT * FROM customer
");

/* =========================
   LOAD ROOM TYPES
========================= */

$roomTypes =
mysqli_query($conn,"
    SELECT * FROM room_type
");

/* =========================
   SELECTED CUSTOMER
========================= */

$selectedCustomer = null;

if($selectedCustomerId != ""){

    $customerSql = "
        SELECT * FROM customer
        WHERE CustomerId='$selectedCustomerId'
    ";

    $customerResult =
    mysqli_query($conn,$customerSql);

    $selectedCustomer =
    mysqli_fetch_assoc($customerResult);
}

/* =========================
   CUSTOMER LEVEL
========================= */

$customerLevel = "New";
$discount = 0;

if($selectedCustomer){

    if($selectedCustomer['StayCount'] >= 10){

        $customerLevel = "VIP";
        $discount = 20;
    }
    else if($selectedCustomer['StayCount'] >= 5){

        $customerLevel = "Regular";
        $discount = 5;
    }
}

/* =========================
   LOAD AVAILABLE ROOMS
========================= */

$rooms = null;

if(
    $checkIn &&
    $checkOut &&
    $selectedRoomType
){

    $sqlRoom = "

    SELECT r.*, rt.RoomTypeName, rt.Price

    FROM room r

    JOIN room_type rt
    ON r.RoomTypeId = rt.RoomTypeId

    WHERE r.RoomTypeId='$selectedRoomType'

    AND r.RoomId NOT IN (

        SELECT RoomId FROM booking

        WHERE (
            ('$checkIn' < CheckOutDate)
            AND
            ('$checkOut' > CheckInDate)
        )

    )

    ";

    $rooms = mysqli_query($conn,$sqlRoom);
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Đặt phòng</title>

    <link rel="stylesheet" href="booking.css">

</head>

<body>

<div class="container">

    <h2>Đặt phòng</h2>

    <form method="GET">

        <!-- KHÁCH HÀNG -->
        <label>Khách hàng</label>

        <select
            name="CustomerId"
            required
            onchange="this.form.submit()"
        >

            <option value="">
                Chọn khách
            </option>

            <?php while($c = mysqli_fetch_assoc($customers)): ?>

                <?php

                $level = "New";

                if($c['StayCount'] >= 10){

                    $level = "VIP";
                }
                else if($c['StayCount'] >= 5){

                    $level = "Regular";
                }

                ?>

                <option
                    value="<?= $c['CustomerId'] ?>"
                    <?= ($selectedCustomerId == $c['CustomerId']) ? 'selected' : '' ?>
                >

                    <?= $c['CustomerName'] ?>
                    -
                    <?= $level ?>

                </option>

            <?php endwhile; ?>

        </select>

      

        <!-- NGÀY -->
        <label>Ngày nhận</label>

        <input
            type="date"
            name="CheckInDate"
            required
            value="<?= $checkIn ?>"
        >

        <label>Ngày trả</label>

        <input
            type="date"
            name="CheckOutDate"
            required
            value="<?= $checkOut ?>"
        >

        <!-- LOẠI PHÒNG -->
        <label>Loại phòng</label>

        <select
            name="RoomTypeId"
            required
            onchange="this.form.submit()"
        >

            <option value="">
                Chọn loại phòng
            </option>

            <?php while($rt = mysqli_fetch_assoc($roomTypes)): ?>

                <option
                    value="<?= $rt['RoomTypeId'] ?>"
                    <?= ($selectedRoomType == $rt['RoomTypeId']) ? 'selected' : '' ?>
                >

                    <?= $rt['RoomTypeName'] ?>

                </option>

            <?php endwhile; ?>

        </select>

        <!-- PHÒNG -->
        <?php if($rooms): ?>

        <label>Phòng trống</label>

        <select name="RoomId" required>

            <option value="">
                Chọn phòng
            </option>

            <?php while($r = mysqli_fetch_assoc($rooms)): ?>

                <option value="<?= $r['RoomId'] ?>">

                    Phòng <?= $r['RoomNumber'] ?>

                    -
                    <?= number_format($r['Price']) ?>đ

                </option>

            <?php endwhile; ?>

        </select>

        <?php endif; ?>
          <?php if($selectedCustomer): ?>

        <div class="customer-info">

            <span class="badge <?= strtolower($customerLevel) ?>">
                <?= $customerLevel ?>
            </span>

            <p>
                Số lần lưu trú:
                <?= $selectedCustomer['StayCount'] ?>
            </p>

            <p>
                Tổng chi tiêu:
                <?= number_format($selectedCustomer['TotalSpent']) ?>đ
            </p>

            <div class="voucher-box">

                <strong>Voucher đề xuất</strong>

                <p>

                    <?php

                    if($customerLevel == "VIP"){

                        echo "Giảm 20% phòng Suite";
                    }
                    else if($customerLevel == "Regular"){

                        echo "Giảm 5% tất cả phòng";
                    }
                    else{

                        echo "Chưa có voucher";
                    }

                    ?>

                </p>

            </div>

        </div>

        <?php endif; ?>
        <?php

$totalPrice = 0;

if(isset($selectedCustomer) && $selectedCustomer){

    // lấy giá phòng theo loại phòng
    $priceSql = "
        SELECT Price
        FROM room_type
        WHERE RoomTypeId='$selectedRoomType'
    ";

    $priceResult =
    mysqli_query($conn,$priceSql);

    $priceRow =
    mysqli_fetch_assoc($priceResult);

    $roomPrice = $priceRow['Price'];

    // tính số đêm
    $days =
    (strtotime($checkOut) - strtotime($checkIn))
    / (60 * 60 * 24);

    // tạm tính
    $subTotal = $roomPrice * $days;

    // giảm giá
    $discountMoney =
    $subTotal * $discount / 100;

    // tổng tiền
    $totalPrice =
    $subTotal - $discountMoney;
}
?>
<!-- TOTAL PRICE -->
<div class="payment-box">

    <h3>Thông tin thanh toán</h3>

    <p>

        Giá phòng:
        <?= number_format($roomPrice) ?>đ

    </p>

    <p>

        Số đêm:
        <?= $days ?>

    </p>

    <p>

        Tạm tính:
        <?= number_format($subTotal) ?>đ

    </p>

    <p>

        Giảm giá:
        -<?= $discount ?>%

    </p>

    <h2>

        Tổng thanh toán:
        <?= number_format($totalPrice) ?>đ

    </h2>

    <!-- PAYMENT -->
    <label>Phương thức thanh toán</label>

    <select name="PaymentMethod">

        <option value="">
            Chọn phương thức
        </option>

        <option value="Cash">
            Tiền mặt
        </option>

        <option value="Banking">
            Chuyển khoản
        </option>

        <option value="Card">
            Thẻ tín dụng
        </option>

        <option value="Momo">
            Ví Momo
        </option>

    </select>

</div>
        <select name="PaymentMethod">

<option value="">
    Chọn phương thức thanh toán
</option>

<option value="Cash">
    Tiền mặt
</option>

<option value="Banking">
    Chuyển khoản
</option>

<option value="Card">
    Thẻ tín dụng
</option>

<option value="Momo">
    Ví Momo
</option>

</select>

        <!-- BUTTON -->
        <button type="submit">

            Đặt phòng

        </button>

    </form>

</div>

</body>
</html>