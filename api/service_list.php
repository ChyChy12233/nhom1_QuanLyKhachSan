<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý dịch vụ</title>

    <link rel="stylesheet" href="../service.css">
</head>

<body>

<div class="service-container">

    <h2>Quản lý dịch vụ</h2>
    <!-- STATS -->
<div class="stats-grid">

    <!-- TỔNG DỊCH VỤ -->
    <div class="stat-card blue">

        <h3>Tổng dịch vụ</h3>

        <h1>4</h1>

        <p>Tất cả dịch vụ</p>

    </div>

    <!-- ĂN UỐNG -->
    <div class="stat-card green">

        <h3>Ăn uống</h3>

        <h1>1</h1>

        <p>Dịch vụ ăn uống</p>

    </div>

    <!-- GIẢI TRÍ -->
    <div class="stat-card orange">

        <h3>Giải trí</h3>

        <h1>1</h1>

        <p>Dịch vụ giải trí</p>

    </div>

    <!-- TIỆN ÍCH -->
    <div class="stat-card purple">

        <h3>Tiện ích</h3>

        <h1>2</h1>

        <p>Dịch vụ tiện ích</p>

    </div>

</div>

<!-- STATUS STATS -->
<div class="stats-grid second-stats">

    <!-- ĐANG HOẠT ĐỘNG -->
    <div class="stat-card active-card">

        <h3>Đang hoạt động</h3>

        <h1>3</h1>

        <p>Dịch vụ khả dụng</p>

    </div>

    <!-- TẠM NGƯNG -->
    <div class="stat-card inactive-card">

        <h3>Tạm ngưng</h3>

        <h1>1</h1>

        <p>Dịch vụ ngưng hoạt động</p>

    </div>

</div>
    <!-- ACTION -->
    <div class="top-actions">

        <input
            type="text"
            placeholder="Tìm dịch vụ..."
        >

        <select>
            <option>Tất cả loại</option>
            <option>Ăn uống</option>
            <option>Giải trí</option>
            <option>Tiện ích</option>
        </select>

        <button>Tìm</button>
     <button
    class="add-btn"
    onclick="toggleServiceForm()"
>
    + Dịch vụ mới
</button>
    </div>

   <!-- SERVICE LAYOUT -->
<div id="serviceLayout" class="service-layout">

    <!-- LEFT : TABLE -->
    <div class="service-table">

        <table>

            <tr>
                <th>Mã DV</th>
                <th>Tên dịch vụ</th>
                <th>Loại</th>
                <th>Giá</th>
                <th>Trạng thái</th>
                <th>Action</th>
            </tr>

            <tr>
                <td>DV001</td>
                <td>Buffet sáng</td>
                <td>Ăn uống</td>
                <td>250.000đ</td>

                <td>
                    <span class="active">
                        Đang hoạt động
                    </span>
                </td>

                <td>
                    <a href="#" class="edit-btn">Sửa</a>
                    <a href="#" class="delete-btn">Xóa</a>
                </td>
            </tr>

            <tr>
                <td>DV002</td>
                <td>Spa thư giãn</td>
                <td>Giải trí</td>
                <td>500.000đ</td>

                <td>
                    <span class="active">
                        Đang hoạt động
                    </span>
                </td>

                <td>
                    <a href="#" class="edit-btn">Sửa</a>
                    <a href="#" class="delete-btn">Xóa</a>
                </td>
            </tr>

            <tr>
                <td>DV003</td>
                <td>Giặt ủi</td>
                <td>Tiện ích</td>
                <td>80.000đ</td>

                <td>
                    <span class="inactive">
                        Tạm ngưng
                    </span>
                </td>

                <td>
                    <a href="#" class="edit-btn">Sửa</a>
                    <a href="#" class="delete-btn">Xóa</a>
                </td>
            </tr>

            <tr>
                <td>DV004</td>
                <td>Thuê xe sân bay</td>
                <td>Tiện ích</td>
                <td>300.000đ</td>

                <td>
                    <span class="active">
                        Đang hoạt động
                    </span>
                </td>

                <td>
                    <a href="#" class="edit-btn">Sửa</a>
                    <a href="#" class="delete-btn">Xóa</a>
                </td>
            </tr>

        </table>

    </div>

  
  
</div>
<!-- SERVICE MODAL -->
<div id="serviceModal" class="modal">

    <div class="modal-content service-modal">

        <!-- HEADER -->
        <div class="modal-header">

            <h3>Thêm dịch vụ</h3>

            <button
                type="button"
                class="close-btn"
                onclick="toggleServiceForm()"
            >
                ×
            </button>

        </div>

        <!-- FORM -->
        <form class="service-grid">

            <!-- MÃ DV -->
            <div class="form-group">

                <label>Mã dịch vụ</label>

                <input
                    type="text"
                    value="DV005"
                    readonly
                >

            </div>

            <!-- TÊN DV -->
            <div class="form-group">

                <label>Tên dịch vụ</label>

                <input
                    type="text"
                    placeholder="Nhập tên dịch vụ"
                >

            </div>

            <!-- LOẠI DV -->
            <div class="form-group">

                <label>Loại dịch vụ</label>

                <select>

                    <option>Ăn uống</option>
                    <option>Giải trí</option>
                    <option>Tiện ích</option>

                </select>

            </div>

            <!-- GIÁ -->
            <div class="form-group">

                <label>Giá dịch vụ</label>

                <input
                    type="number"
                    placeholder="Nhập giá dịch vụ"
                >

            </div>

            <!-- TRẠNG THÁI -->
            <div class="form-group">

                <label>Trạng thái</label>

                <select>

                    <option>Đang hoạt động</option>
                    <option>Tạm ngưng</option>
                    <option>Ngưng hoạt động</option>

                </select>

            </div>

            <!-- BUTTON -->
            <div class="full">

                <button
                    type="submit"
                    class="submit-btn"
                >
                    Lưu dịch vụ
                </button>

            </div>

        </form>

    </div>

</div>

<script>

function toggleServiceForm(){

    document
        .getElementById("serviceModal")
        .classList
        .toggle("show");
}

</script>

</body>
</html>