<!DOCTYPE html>
<html lang="vi">
<head>

    <meta charset="UTF-8">

    <title>Thêm khách hàng</title>

    <link rel="stylesheet" href="customer.css">

</head>

<body>

<div class="form-container">

    <h2>Thêm khách hàng</h2>

    <form
        class="form-grid"
        method="POST"
        action="save_customer.php"
    >

        <!-- MÃ KH -->
        <div class="form-group">

            <label>Mã khách hàng</label>

            <input
                type="text"
                name="CustomerId"
                placeholder="KH001"
                required
            >

        </div>

        <!-- HỌ TÊN -->
        <div class="form-group">

            <label>Họ và tên</label>

            <input
                type="text"
                name="CustomerName"
                required
            >

        </div>

        <!-- CCCD -->
        <div class="form-group">

            <label>CCCD</label>

            <input
                type="text"
                name="CCCD"
                required
            >

        </div>

        <!-- SDT -->
        <div class="form-group">

            <label>Số điện thoại</label>

            <input
                type="text"
                name="PhoneNumber"
                required
            >

        </div>

        <!-- EMAIL -->
        <div class="form-group">

            <label>Email</label>

            <input
                type="email"
                name="Email"
                required
            >

        </div>

        <!-- NGÀY SINH -->
        <div class="form-group">

            <label>Ngày sinh</label>

            <input
                type="date"
                name="Birthday"
                required
            >

        </div>

        <!-- GIỚI TÍNH -->
        <div class="form-group">

            <label>Giới tính</label>

            <select name="Gender">

                <option value="Nam">
                    Nam
                </option>

                <option value="Nữ">
                    Nữ
                </option>

            </select>

        </div>

        <!-- QUỐC TỊCH -->
        <div class="form-group">

            <label>Quốc tịch</label>

            <input
                type="text"
                name="Nationality"
                value="Việt Nam"
                required
            >

        </div>

        <!-- ĐỊA CHỈ -->
        <div class="form-group full">

            <label>Địa chỉ</label>

            <input
                type="text"
                name="Address"
                required
            >

        </div>

        <!-- LOẠI KH -->
        <div class="form-group">

            <label>Loại khách hàng</label>

            <input
                type="text"
                name="CustomerLevel"
                value="New"
                readonly
            >

        </div>

        <!-- SỐ LẦN LƯU TRÚ -->
        <div class="form-group">

            <label>Số lần lưu trú</label>

            <input
                type="number"
                name="StayCount"
                value="0"
                readonly
            >

        </div>

        <!-- TỔNG CHI TIÊU -->
        <div class="form-group">

            <label>Tổng chi tiêu</label>

            <input
                type="text"
                name="TotalSpent"
                value="0"
                readonly
            >

        </div>

        <!-- NGÀY TẠO -->
        <div class="form-group">

            <label>Ngày tạo</label>

            <input
                type="date"
                name="CreatedDate"
                value="<?= date('Y-m-d') ?>"
                readonly
            >

        </div>

        <!-- BUTTON -->
        <button
            type="submit"
            class="submit-btn full"
        >

            Thêm khách hàng

        </button>

    </form>

</div>

</body>
</html>