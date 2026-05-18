<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sự cố</title>

    <link rel="stylesheet" href="../incident.css">
</head>

<body>

<div class="incident-container">

    <h2>Quản lý sự cố</h2>

    <!-- TOP ACTION -->
    <div class="top-actions">

        <input
            type="text"
            placeholder="Tìm sự cố..."
        >

        <select>
            <option>Tất cả trạng thái</option>
            <option>Đang xử lý</option>
            <option>Đã xử lý</option>
        </select>

        <button>Tìm</button>

    </div>

    <!-- TABLE -->
    <table>

        <tr>
            <th>Mã SC</th>
            <th>Phòng</th>
            <th>Nội dung</th>
            <th>Người báo</th>
            <th>Ngày báo</th>
            <th>Trạng thái</th>
            <th>Action</th>
        </tr>

        <tr>
            <td>SC001</td>
            <td>101</td>
            <td>Máy lạnh không hoạt động</td>
            <td>Đoàn Cường</td>
            <td>14/05/2026</td>

            <td>
                <span class="pending">
                    Đang xử lý
                </span>
            </td>

            <td>
                <a href="#" class="edit-btn">
                    Cập nhật
                </a>
            </td>
        </tr>

        <tr>
            <td>SC002</td>
            <td>702</td>
            <td>Đèn phòng bị hỏng</td>
            <td>Nguyễn Quốc Cường</td>
            <td>13/05/2026</td>

            <td>
                <span class="done">
                    Đã xử lý
                </span>
            </td>

            <td>
                <a href="#" class="edit-btn">
                    Xem
                </a>
            </td>
        </tr>

        <tr>
            <td>SC003</td>
            <td>702</td>
            <td>Wifi kết nối chậm</td>
            <td>Đoàn Cường</td>
            <td>12/05/2026</td>

            <td>
                <span class="pending">
                    Đang xử lý
                </span>
            </td>

            <td>
                <a href="#" class="edit-btn">
                    Cập nhật
                </a>
            </td>
        </tr>

        <tr>
            <td>SC004</td>
            <td>105</td>
            <td>TV không lên nguồn</td>
            <td>Phương Thiên Lộc</td>
            <td>11/05/2026</td>

            <td>
                <span class="done">
                    Đã xử lý
                </span>
            </td>

            <td>
                <a href="#" class="edit-btn">
                    Xem
                </a>
            </td>
        </tr>

    </table>

</div>

</body>
</html>