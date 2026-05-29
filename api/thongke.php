<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Thống kê doanh thu</title>

    <link rel="stylesheet" href="thongke.css">

</head>

<body>

<div class="container">

    <!-- TITLE -->
    <h2>Báo cáo thống kê</h2>

    <!-- FILTER -->
    <div class="filter-box">

        <div class="form-group">

            <label>Từ ngày</label>

            <input type="date">

        </div>

        <div class="form-group">

            <label>Đến ngày</label>

            <input type="date">

        </div>

        <div class="form-group">

            <label>Loại phòng</label>

            <select>

                <option>Tất cả loại phòng</option>

                <option>Standard</option>

                <option>Deluxe</option>

                <option>Suite</option>

            </select>

        </div>

        <button class="filter-btn">

            Thống kê

        </button>

    </div>

    <!-- CARDS -->
    <div class="stats-grid">

        <div class="stat-card blue">

            <h3>3</h3>

            <p>Tổng lượt đặt phòng</p>

        </div>

        <div class="stat-card green">

            <h3>5.500.000</h3>

            <p>Tổng doanh thu</p>

        </div>

        <div class="stat-card orange">

            <h3>85%</h3>

            <p>Tỉ lệ lấp đầy</p>

        </div>

        <div class="stat-card red">

            <h3>0</h3>

            <p>Hóa đơn chưa thanh toán</p>

        </div>

    </div>

    <!-- TABLE -->
    <div class="table-box">

        <div class="table-header">

            <h3>Chi tiết doanh thu</h3>

        </div>

        <table>

            <tr>

                <th>Loại phòng</th>

                <th>Số lượt thuê</th>

                <th>Doanh thu</th>

                <th>Tỉ lệ sử dụng</th>

            </tr>

            <tr>

                <td>Standard</td>

                <td>0</td>

                <td>0</td>

                <td>0</td>

            </tr>

            <tr>

                <td>Deluxe</td>

                <td>0</td>

                <td>0</td>

                <td>0</td>

            </tr>

            <tr>

                <td>VIP</td>

                <td>0</td>

                <td>0</td>

                <td>0</td>

            </tr>

        </table>

    </div>

    <!-- CHART -->
    <div class="chart-box">

        <div class="chart-header">

            <h3>Biểu đồ doanh thu</h3>

        </div>

        <div class="fake-chart">

            <div class="bar standard">

                <span>120tr</span>

            </div>

            <div class="bar deluxe">

                <span>180tr</span>

            </div>

            <div class="bar suite">

                <span>150tr</span>

            </div>

        </div>

        <div class="chart-label">

            <p>Standard</p>

            <p>Deluxe</p>

            <p>Suite</p>

        </div>

    </div>

</div>

</body>
</html>