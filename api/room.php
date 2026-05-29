<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Quản lý phòng</title>

    <link rel="stylesheet" href="room.css">

</head>

<body>

<div class="container">

    <!-- TITLE -->
    <h2>Quản lý phòng</h2>

    <!-- STATS -->
    <div class="stats-grid">

        <div class="stat-card blue">

            <h3>2</h3>

            <p>Tổng số phòng</p>

        </div>

        <div class="stat-card green">

            <h3>1</h3>

            <p>Phòng trống</p>

        </div>

        <div class="stat-card red">

            <h3>1</h3>

            <p>Đang được thuê</p>

        </div>

    </div>

    <!-- TOP BAR -->
    <div class="top-bar">

        <input
            type="text"
            placeholder="Tìm mã phòng..."
        >

        <select>

            <option>Tất cả trạng thái</option>

            <option>Trống</option>

            <option>Đang được thuê</option>

        </select>

        <button class="search-btn">

            Tìm kiếm

        </button>

        <button
            class="add-btn"
            onclick="openRoomModal()"
        >

            + Thêm phòng

        </button>

    </div>

    <!-- TABLE -->
    <table>

        <tr>

            <th>Mã phòng</th>

            <th>Loại phòng</th>

            <th>Trạng thái</th>

            <th>Ghi chú</th>

            <th>Action</th>

        </tr>

        <tr>

            <td>P101</td>

            <td>Standard</td>

            <td>
                <span class="empty">
                    Trống
                </span>
            </td>

            <td>Phòng sạch</td>

            <td>

                <button
                    class="edit-btn"
                    onclick="openRoomModal()"
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

            <td>P202</td>

            <td>Deluxe</td>

            <td>
                <span class="rented">
                    Đang được thuê
                </span>
            </td>

            <td>Khách VIP</td>

            <td>

                <button
                    class="edit-btn"
                    onclick="openRoomModal()"
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

<!-- ROOM MODAL -->
<div id="roomModal" class="modal">

    <div class="modal-content">

        <!-- HEADER -->
        <div class="modal-header">

            <h3>Thêm phòng</h3>

            <span
                class="close-btn"
                onclick="closeRoomModal()"
            >
                ×
            </span>

        </div>

        <!-- FORM -->
        <form class="form-grid">

            <div class="form-group">

                <label>Mã phòng</label>

                <input
                    type="text"
                    value="P301"
                    readonly
                >

            </div>

            <div class="form-group">

                <label>Loại phòng</label>

                <select>

                    <option>Standard</option>

                    <option>Deluxe</option>

                    <option>Suite</option>

                </select>

            </div>

            <div class="form-group">

                <label>Trạng thái</label>

                <select>

                    <option>Trống</option>

                    <option>Đang được thuê</option>

                </select>

            </div>

            <div class="form-group">

                <label>Ghi chú</label>

                <input
                    type="text"
                    placeholder="Thêm ghi chú"
                >

            </div>

            <button
                type="submit"
                class="save-btn full"
            >

                Lưu phòng

            </button>

        </form>

    </div>

</div>

<!-- DELETE MODAL -->
<div id="deleteModal" class="modal">

    <div class="delete-modal">

        <h3>Xác nhận xóa</h3>

        <p>
            Bạn chắc chắn muốn xóa phòng này?
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
                Xóa phòng
            </button>

        </div>

    </div>

</div>

<script>

function openRoomModal(){

    document
        .getElementById("roomModal")
        .classList.add("show");
}

function closeRoomModal(){

    document
        .getElementById("roomModal")
        .classList.remove("show");
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