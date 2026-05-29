<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Quản lý hóa đơn</title>

    <link rel="stylesheet" href="../invoice.css">

</head>

<body>

<div class="container">

    <!-- HEADER -->
    <div class="page-header">

        <h2>Quản lý hóa đơn</h2>

      <!-- BUTTON THÊM HÓA ĐƠN -->
<button
    class="add-btn"
    onclick="toggleInvoiceForm()"
>
    + Thêm hóa đơn
</button>

<!-- POPUP -->
<div id="invoiceModal" class="modal">

    <div class="modal-content invoice-modal">

        <!-- HEADER -->
        <div class="modal-header">

            <h3>Thêm hóa đơn</h3>

            <button
                class="close-btn"
                onclick="toggleInvoiceForm()"
            >
                ×
            </button>

        </div>

        <!-- FORM -->
        <form class="invoice-grid">

            <!-- MÃ HÓA ĐƠN -->
            <div class="form-group">

                <label>Mã hóa đơn</label>

                <input
                    type="text"
                    value="HD008"
                    readonly
                >

            </div>

            <!-- KHÁCH HÀNG -->
            <div class="form-group">

                <label>Khách hàng</label>

                <select onchange="fillInvoiceData()">

                    <option value="">
                        -- Chọn khách hàng --
                    </option>

                    <option value="KH001">
                        KH001 - Vũ Minh Đức
                    </option>

                    <option value="KH002">
                        KH002 - Vũ Mai Vân
                    </option>

                </select>

            </div>

            <!-- PHÒNG -->
            <div class="form-group">

                <label>Phòng</label>

                <input
                    type="text"
                    id="room"
                    readonly
                >

            </div>

            <!-- LOẠI PHÒNG -->
            <div class="form-group">

                <label>Loại phòng</label>

                <input
                    type="text"
                    id="roomType"
                    readonly
                >

            </div>

            <!-- GIÁ PHÒNG -->
            <div class="form-group">

                <label>Giá phòng</label>

                <input
                    type="text"
                    id="roomPrice"
                    readonly
                >

            </div>

            <!-- SỐ NGÀY Ở -->
            <div class="form-group">

                <label>Số ngày ở</label>

                <input
                    type="text"
                    id="stayDays"
                    readonly
                >

            </div>

            <!-- DỊCH VỤ -->
            <div class="form-group">

                <label>Dịch vụ sử dụng</label>

                <input
                    type="text"
                    id="service"
                    readonly
                >

            </div>

            <!-- VOUCHER -->
            <div class="form-group">

                <label>Voucher</label>

                <input
                    type="text"
                    id="voucher"
                    readonly
                >

            </div>

            <!-- THÀNH TIỀN -->
            <div class="form-group full">

                <label>Thành tiền</label>

                <input
                    type="text"
                    id="total"
                    readonly
                >

            </div>

            <!-- TRẠNG THÁI -->
            <div class="form-group full">

                <label>Trạng thái hóa đơn</label>

                <input
                    type="text"
                    value="Chưa thanh toán"
                    readonly
                >

            </div>

            <!-- BUTTON -->
            <div class="invoice-actions full">

               <button
    type="button"
    class="save-btn"
    onclick="toggleConfirmInvoice()"
>

    Thêm

</button>

            </div>

        </form>

    </div>

</div>

    </div>

    <!-- STATS -->
    <div class="stats-grid">

        <div class="stat-card green">

            <h3>Đã thanh toán</h3>

            <h1>3</h1>

        </div>

        <div class="stat-card orange">

            <h3>Chưa thanh toán</h3>

            <h1>2</h1>

        </div>

        <div class="stat-card red">

            <h3>Đã hủy</h3>

            <h1>2</h1>

        </div>

    </div>

    <!-- SEARCH -->
    <div class="search-bar">

        <input
            type="text"
            placeholder="Tìm kiếm hóa đơn..."
        >

        <button>Tìm kiếm</button>

    </div>

    <!-- TABLE -->
    <div class="table-wrapper">

        <table>

            <tr>

                <th>Mã hóa đơn</th>

                <th>Mã khách hàng</th>

                <th>Mã nhân viên</th>

                <th>Trạng thái</th>

                <th>Thành tiền</th>

                <th>Thao tác</th>

            </tr>

            <tr>

                <td>HD001</td>

                <td>KH001</td>

                <td>NV001</td>

                <td>

                    <span class="status paid">
                        Đã thanh toán
                    </span>

                </td>

                <td>5,500,000 VNĐ</td>

                <td>

                    <button
    class="detail-btn"
    onclick="toggleInvoiceDetail()"
>

    Chi tiết

</button>

                </td>

            </tr>

        </table>

    </div>

</div>

<script src="invoice.js"></script>
<!-- DETAIL MODAL -->
<div id="invoiceDetailModal" class="modal">

    <div class="modal-content detail-modal">

        <!-- HEADER -->
       <div class="modal-header">

    <h3>Chi tiết hóa đơn</h3>

    <button
        class="close-btn"
        onclick="toggleInvoiceDetail()"
    >
        ×
    </button>

</div>

        <!-- CONTENT -->
        <div class="detail-grid">

            <div class="detail-item">

                <label>Mã hóa đơn</label>

                <input
                    type="text"
                    value="HD001"
                    readonly
                >

            </div>

            <div class="detail-item">

                <label>Mã khách hàng</label>

                <input
                    type="text"
                    value="KH001"
                    readonly
                >

            </div>

            <div class="detail-item">

                <label>Tên khách hàng</label>

                <input
                    type="text"
                    value="Vũ Minh Đức"
                    readonly
                >

            </div>

            <div class="detail-item">

                <label>Mã nhân viên</label>

                <input
                    type="text"
                    value="NV001"
                    readonly
                >

            </div>

            <div class="detail-item">

                <label>Phòng</label>

                <input
                    type="text"
                    value="P101"
                    readonly
                >

            </div>

            <div class="detail-item">

                <label>Loại phòng</label>

                <input
                    type="text"
                    value="VIP"
                    readonly
                >

            </div>

            <div class="detail-item">

                <label>Số ngày ở</label>

                <input
                    type="text"
                    value="3"
                    readonly
                >

            </div>

            <div class="detail-item">

                <label>Dịch vụ sử dụng</label>

                <input
                    type="text"
                    value="Buffet sáng, Spa"
                    readonly
                >

            </div>

            <div class="detail-item">

                <label>Voucher</label>

                <input
                    type="text"
                    value="Giảm 10%"
                    readonly
                >

            </div>

            <div class="detail-item">

                <label>Trạng thái</label>

                <input
                    type="text"
                    value="Đã thanh toán"
                    readonly
                >

            </div>

            <div class="detail-item full">

                <label>Thành tiền</label>

                <input
                    type="text"
                    value="5,500,000 VNĐ"
                    readonly
                >

            </div>

        </div>

        <!-- BUTTON -->
        <div class="detail-actions">

            <button class="delete-btn-modal">

                Xóa hóa đơn

            </button>

        </div>

    </div>

</div>
<script>

function toggleInvoiceDetail(){

    document
        .getElementById("invoiceDetailModal")
        .classList
        .toggle("show");
}

</script>
<script>

function toggleInvoiceForm(){

    document
        .getElementById("invoiceModal")
        .classList
        .toggle("show");
}

/* AUTO FILL */
function fillInvoiceData(){

    document.getElementById("room").value = "P101";

    document.getElementById("roomType").value = "VIP";

    document.getElementById("roomPrice").value = "1,500,000 VNĐ";

    document.getElementById("stayDays").value = "3";

    document.getElementById("service").value = "Buffet sáng, Spa";

    document.getElementById("voucher").value = "Giảm 10%";

    document.getElementById("total").value = "5,500,000 VNĐ";
}

</script>
<!-- BOX XÁC NHẬN -->
<div id="confirmInvoiceModal" class="delete-modal">

    <div class="delete-box">

        <h3>Xác nhận thêm hóa đơn</h3>

        <p>
            Bạn chắc chắn muốn thêm hóa đơn này không?
        </p>

        <div class="delete-actions">

            <!-- HỦY -->
            <button
                class="cancel-btn"
                onclick="toggleConfirmInvoice()"
            >
                Hủy
            </button>

            <!-- XÁC NHẬN -->
            <button
                class="confirm-btn"
                onclick="submitInvoice()"
            >
                Xác nhận
            </button>

        </div>

    </div>

</div>
<script>

/* POPUP THÊM HÓA ĐƠN */
function toggleInvoiceForm(){

    document
        .getElementById("invoiceModal")
        .classList
        .toggle("show");
}

/* POPUP XÁC NHẬN */
function toggleConfirmInvoice(){

    document
        .getElementById("confirmInvoiceModal")
        .classList
        .toggle("show");
}

/* SUBMIT */
function submitInvoice(){

    alert("Thêm hóa đơn thành công!");

    toggleConfirmInvoice();

    toggleInvoiceForm();
}

</script>
</body>
</html>