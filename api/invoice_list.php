<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý hóa đơn</title>

    <link rel="stylesheet" href="../invoice.css">
</head>

<body>

<div class="invoice-container">

    <h2>Quản lý hóa đơn</h2>

    <!-- ACTION -->
    <div class="top-actions">

        <input
            type="text"
            placeholder="Tìm mã hóa đơn..."
        >

        <select>
            <option>Tất cả trạng thái</option>
            <option>Đã thanh toán</option>
            <option>Chưa thanh toán</option>
        </select>

        <input type="date">

        <button>Tìm kiếm</button>

    </div>

    <!-- SUMMARY -->
    <div class="summary-cards">

        <div class="card total">
            <h3>Tổng hóa đơn</h3>
            <p>350</p>
        </div>

        <div class="card paid">
            <h3>Đã thanh toán</h3>
            <p>290</p>
        </div>

        <div class="card unpaid">
            <h3>Chưa thanh toán</h3>
            <p>60</p>
        </div>

        <div class="card revenue">
            <h3>Tổng doanh thu</h3>
            <p>1.250.000.000đ</p>
        </div>

    </div>

    <!-- TABLE -->
    <div class="invoice-table">

        <table>

            <tr>
                <th>Mã HĐ</th>
                <th>Khách hàng</th>
                <th>Phòng</th>
                <th>Tổng tiền</th>
                <th>Ngày lập</th>
                <th>Trạng thái</th>
                <th>Action</th>
            </tr>

            <tr>
                <td>HD001</td>
                <td>Vũ Minh Đức</td>
                <td>101</td>
                <td>5.500.000đ</td>
                <td>12/05/2026</td>

                <td>
                    <span class="paid-status">
                        Đã thanh toán
                    </span>
                </td>

                <td>
                    <a href="#" class="view-btn">
                        Chi tiết
                    </a>
                </td>
            </tr>

            <tr>
                <td>HD002</td>
                <td>Hà Anh Tuấn</td>
                <td>205</td>
                <td>3.200.000đ</td>
                <td>13/05/2026</td>

                <td>
                    <span class="unpaid-status">
                        Chưa thanh toán
                    </span>
                </td>

                <td>
                    <a href="#" class="view-btn">
                        Chi tiết
                    </a>
                </td>
            </tr>

            <tr>
                <td>HD003</td>
                <td>Vũ Mai Vân</td>
                <td>308</td>
                <td>8.900.000đ</td>
                <td>14/05/2026</td>

                <td>
                    <span class="paid-status">
                        Đã thanh toán
                    </span>
                </td>

                <td>
                    <a href="#" class="view-btn">
                        Chi tiết
                    </a>
                </td>
            </tr>

        </table>

    </div>

</div>

</body>
</html>