<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Quản lý vật tư</title>

    <link rel="stylesheet" href="facility.css">

</head>

<body>

<div class="container">

    <!-- TITLE -->
    <h2>Quản lý vật tư</h2>

    <!-- STATS -->
    <div class="stats-grid">

        <div class="stat-card blue">

            <h3>125</h3>

            <p>Tổng vật tư</p>

        </div>

        <div class="stat-card green">

            <h3>2.350</h3>

            <p>Số lượng tồn kho</p>

        </div>

        <div class="stat-card orange">

            <h3>15</h3>

            <p>Phiếu nhập hôm nay</p>

        </div>

        <div class="stat-card red">

            <h3>8</h3>

            <p>Vật tư sắp hết</p>

        </div>

    </div>

    <!-- TOP BAR -->
    <div class="top-bar">

        <input
            type="text"
            placeholder="Tìm vật tư..."
        >

        <select>

            <option>Tất cả loại vật tư</option>

            <option>Thiết bị điện</option>

            <option>Thiết bị vệ sinh</option>

            <option>Nội thất</option>

            <option>Tiện nghi phòng</option>

        </select>

        <button class="search-btn">

            Tìm kiếm

        </button>

        <button
            class="add-btn"
            onclick="openMaterialModal()"
        >

            + Thêm vật tư

        </button>

        <button
            class="import-btn"
            onclick="openImportModal()"
        >

            + Nhập vật tư

        </button>

    </div>

    <!-- TABLE -->
    <table>

        <tr>

            <th>Mã VT</th>

            <th>Tên vật tư</th>

            <th>Loại vật tư</th>

            <th>Giá</th>

            <th>Tồn kho</th>

            <th>Action</th>

        </tr>

        <tr>

            <td>VT001</td>

            <td>Máy lạnh</td>

            <td>Thiết bị điện</td>

            <td>12.000.000đ</td>

            <td>
                <span class="stock good">
                    25
                </span>
            </td>

            <td>

                <button
                    class="edit-btn"
                    onclick="openMaterialModal()"
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

            <td>VT002</td>

            <td>Khăn tắm</td>

            <td>Tiện nghi phòng</td>

            <td>120.000đ</td>

            <td>
                <span class="stock warning">
                    5
                </span>
            </td>

            <td>

                <button
                    class="edit-btn"
                    onclick="openMaterialModal()"
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

            <td>VT003</td>

            <td>Đèn ngủ</td>

            <td>Nội thất</td>

            <td>350.000đ</td>

            <td>
                <span class="stock danger">
                    2
                </span>
            </td>

            <td>

                <button
                    class="edit-btn"
                    onclick="openMaterialModal()"
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

<!-- MATERIAL MODAL -->
<div id="materialModal" class="modal">

    <div class="modal-content">

        <div class="modal-header">

            <h3>Thêm vật tư</h3>

            <span
                class="close-btn"
                onclick="closeMaterialModal()"
            >
                ×
            </span>

        </div>

        <form class="form-grid">

            <div class="form-group">

                <label>Mã vật tư</label>

                <input
                    type="text"
                    value="VT004"
                    readonly
                >

            </div>

            <div class="form-group">

                <label>Tên vật tư</label>

                <input
                    type="text"
                    placeholder="Nhập tên vật tư"
                >

            </div>

            <div class="form-group">

                <label>Loại vật tư</label>

                <select>

                    <option>Thiết bị điện</option>

                    <option>Thiết bị vệ sinh</option>

                    <option>Nội thất</option>

                    <option>Tiện nghi phòng</option>

                </select>

            </div>

            <div class="form-group">

                <label>Giá vật tư</label>

                <input
                    type="number"
                    placeholder="Nhập giá"
                >

            </div>

            <button
                type="submit"
                class="save-btn full"
            >

                Lưu vật tư

            </button>

        </form>

    </div>

</div>

<!-- IMPORT MODAL -->
<div id="importModal" class="modal">

    <div class="modal-content">

        <div class="modal-header">

            <h3>Nhập vật tư</h3>

            <span
                class="close-btn"
                onclick="closeImportModal()"
            >
                ×
            </span>

        </div>

        <form class="form-grid">

            <div class="form-group">

                <label>Mã phiếu nhập</label>

                <input
                    type="text"
                    value="PN001"
                    readonly
                >

            </div>

            <div class="form-group">

                <label>Nhân viên nhập</label>

                <input
                    type="text"
                    value="NV001"
                    readonly
                >

            </div>

            <div class="form-group">

                <label>Ngày nhập</label>

                <input type="date">

            </div>

            <div class="form-group">

                <label>Vật tư</label>

                <select>

                    <option>VT001 - Máy lạnh</option>

                    <option>VT002 - Khăn tắm</option>

                    <option>VT003 - Đèn ngủ</option>

                </select>

            </div>

            <div class="form-group">

                <label>Số lượng</label>

                <input
                    type="number"
                    placeholder="Nhập số lượng"
                >

            </div>

            <div class="form-group">

                <label>Giá nhập</label>

                <input
                    type="number"
                    placeholder="Nhập giá nhập"
                >

            </div>

            <button
                type="submit"
                class="save-btn full"
            >

                Lưu phiếu nhập

            </button>

        </form>

    </div>

</div>

<!-- DELETE MODAL -->
<div id="deleteModal" class="modal">

    <div class="delete-modal">

        <h3>Xác nhận xóa</h3>

        <p>
            Bạn chắc chắn muốn xóa vật tư này?
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
                Xóa vật tư
            </button>

        </div>

    </div>

</div>

<script>

function openMaterialModal(){

    document
        .getElementById("materialModal")
        .classList.add("show");
}

function closeMaterialModal(){

    document
        .getElementById("materialModal")
        .classList.remove("show");
}

function openImportModal(){

    document
        .getElementById("importModal")
        .classList.add("show");
}

function closeImportModal(){

    document
        .getElementById("importModal")
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