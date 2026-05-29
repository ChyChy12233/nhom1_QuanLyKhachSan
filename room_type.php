<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Quản lý loại phòng</title>

    <link rel="stylesheet" href="../room_type.css">

</head>

<body>

<div class="container">

    <!-- TITLE -->
    <h2>Quản lý loại phòng</h2>

    <!-- STATS -->
    <div class="stats-grid">

        <div class="stat-card blue">

            <h3>12</h3>

            <p>Tổng loại phòng</p>

        </div>

        <div class="stat-card green">

            <h3>8</h3>

            <p>Đang hoạt động</p>

        </div>

        <div class="stat-card orange">

            <h3>3</h3>

            <p>Tạm ngưng</p>

        </div>

        <div class="stat-card red">

            <h3>1</h3>

            <p>Ngưng hoạt động</p>

        </div>

    </div>

    <!-- ACTION -->
    <div class="top-bar">

        <input
            type="text"
            placeholder="Tìm loại phòng..."
        >

        <select>

            <option>Tất cả trạng thái</option>

            <option>Đang hoạt động</option>

            <option>Tạm ngưng hoạt động</option>

            <option>Ngưng hoạt động</option>

        </select>

        <button class="search-btn">

            Tìm kiếm

        </button>

        <button
            class="add-btn"
            onclick="openRoomTypeModal()"
        >

            + Thêm loại phòng

        </button>

    </div>

    <!-- TABLE -->
    <table>

        <tr>

            <th>Mã loại phòng</th>

            <th>Tên loại phòng</th>

            <th>Đơn giá</th>

            <th>Trạng thái</th>

            <th>Action</th>

        </tr>

        <tr>

            <td>LP001</td>

            <td>Standard</td>

            <td>500.000đ</td>

            <td>
                <span class="active">
                    Đang hoạt động
                </span>
            </td>

            <td>

                <button
                    class="edit-btn"
                    onclick="openEditModal()"
                >
                    Sửa
                </button>

                <button
                    class="delete-btn"
                    onclick="openDeleteModal()"
                >
                    Xóa
                </button>

            </td>

        </tr>

        <tr>

            <td>LP002</td>

            <td>Deluxe</td>

            <td>900.000đ</td>

            <td>
                <span class="pause">
                    Tạm ngưng hoạt động
                </span>
            </td>

            <td>

                <button
                    class="edit-btn"
                    onclick="openEditModal()"
                >
                    Sửa
                </button>

                <button
                    class="delete-btn"
                    onclick="openDeleteModal()"
                >
                    Xóa
                </button>

            </td>

        </tr>

        <tr>

            <td>LP003</td>

            <td>Suite</td>

            <td>1.500.000đ</td>

            <td>
                <span class="inactive">
                    Ngưng hoạt động
                </span>
            </td>

            <td>

                <button
                    class="edit-btn"
                    onclick="openEditModal()"
                >
                    Sửa
                </button>

                <button
                    class="delete-btn"
                    onclick="openDeleteModal()"
                >
                    Xóa
                </button>

            </td>

        </tr>

    </table>

</div>

<!-- ADD / EDIT MODAL -->
<div id="roomTypeModal" class="modal">

    <div class="modal-content">

        <div class="modal-header">

            <h3>Thêm loại phòng</h3>

            <span
                class="close-btn"
                onclick="closeRoomTypeModal()"
            >
                ×
            </span>

        </div>

        <form class="form-grid">

            <div class="form-group">

                <label>Mã loại phòng</label>

                <input
                    type="text"
                    value="LP004"
                    readonly
                >

            </div>

            <div class="form-group">

                <label>Tên loại phòng</label>

                <input
                    type="text"
                    placeholder="Nhập tên loại phòng"
                >

            </div>

            <div class="form-group">

                <label>Đơn giá</label>

                <input
                    type="number"
                    placeholder="Nhập đơn giá"
                >

            </div>

            <div class="form-group">

                <label>Trạng thái</label>

                <select>

                    <option>Đang hoạt động</option>

                    <option>Tạm ngưng hoạt động</option>

                    <option>Ngưng hoạt động</option>

                </select>

            </div>

            <button
                type="submit"
                class="save-btn full"
            >

                Lưu loại phòng

            </button>

        </form>

    </div>

</div>

<!-- DELETE MODAL -->
<div id="deleteModal" class="modal">

    <div class="delete-modal">

        <h3>Xác nhận xóa</h3>

        <p>
            Bạn chắc chắn muốn xóa loại phòng này?
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
                Xóa loại phòng
            </button>

        </div>

    </div>

</div>

<script>

function openRoomTypeModal(){

    document
        .getElementById("roomTypeModal")
        .classList.add("show");
}

function closeRoomTypeModal(){

    document
        .getElementById("roomTypeModal")
        .classList.remove("show");
}

function openEditModal(){

    document
        .getElementById("roomTypeModal")
        .classList.add("show");
}

function openDeleteModal(){

    document
        .getElementById("deleteModal")
        .classList.add("show");
}

function closeDeleteModal(){

    document
        .getElementById("deleteModal")
        .classList.remove("show");
}

</script>

</body>
</html>