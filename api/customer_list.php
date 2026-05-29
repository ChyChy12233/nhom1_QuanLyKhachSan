<?php
$conn = mysqli_connect("localhost","root","","hotel");
$deleted = isset($_GET['deleted']);
$updated =
isset($_GET['updated']);
$keyword = isset($_GET['keyword']) 
? mysqli_real_escape_string($conn, $_GET['keyword']) 
: "";
/* AUTO CUSTOMER ID */

$getLast = mysqli_query(
    $conn,
    "

    SELECT CustomerId

    FROM customer

    ORDER BY CustomerId DESC

    LIMIT 1

    "
);

if(mysqli_num_rows($getLast) > 0){

    $lastRow = mysqli_fetch_assoc($getLast);

    $lastId = $lastRow['CustomerId'];

    $number = (int) substr($lastId, 2);

    $number++;

    $nextCustomerId =
    "KH" .
    str_pad($number, 3, "0", STR_PAD_LEFT);

}
else{

    $nextCustomerId = "KH001";
}
$level =
isset($_GET['level'])
? $_GET['level']
: '';

$sql = "SELECT * FROM customer WHERE 1";

if ($keyword != "") {

    $sql .= " AND (
        LOWER(CustomerName) LIKE LOWER('%$keyword%') 
        OR PhoneNumber LIKE '%$keyword%'
        OR LOWER(Email) LIKE LOWER('%$keyword%')
    )";
}

if($level != ""){

    if($level == "VIP"){

        $sql .= " AND StayCount >= 10";
    }
    else if($level == "Regular"){

        $sql .= " AND StayCount >= 5
                  AND StayCount < 10";
    }
    else{

        $sql .= " AND StayCount < 5";
    }
}
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Danh sách khách hàng</title>
    <link rel="stylesheet" href="../customer.css">

</head>

<body>

<div class="container customer-page">

    <h2>Danh sách khách hàng</h2>

    <!-- TOP ACTION -->
    <div class="top-actions">

        <form method="GET" class="search-box">

            <input
                type="text"
                name="keyword"
                placeholder="Tìm khách..."
                value="<?= $keyword ?>"
            >
<select name="level">

<option value="">
    Lọc hạng khách hàng
</option>

<option
    value="VIP"
    <?= ($level=='VIP')?'selected':'' ?>
>
    VIP
</option>

<option
    value="Regular"
    <?= ($level=='Regular')?'selected':'' ?>
>
    Regular
</option>

<option
    value="New"
    <?= ($level=='New')?'selected':'' ?>
>
    New
</option>
</select>

            <button type="submit">
                Tìm
            </button>

        </form>

        <!-- ADD BUTTON -->
      <button
    class="add-btn"
    onclick="toggleForm()"
>
    + Thêm khách hàng
</button>
    </div>

    <!-- TABLE -->
    <table>

        <tr>
        
    <th>ID</th>

    <th>Họ Tên</th>

    <th>Số Điện Thoại</th>

    <th>Email</th>

    <th>Số lần lưu trú</th>

    <th>Tổng chi tiêu</th>

    <th>Hạng Khách Hàng</th>

    <th>Action</th>

</tr>

<?php if (mysqli_num_rows($result) > 0): ?>

<?php while($row = mysqli_fetch_assoc($result)): ?>

       <tr onclick="viewCustomer(
'<?= $row['CustomerId'] ?>',
'<?= $row['CustomerName'] ?>',
'<?= $row['PhoneNumber'] ?>',
'<?= $row['Email'] ?>',
'<?= $row['CCCD'] ?>',
'<?= $row['Birthday'] ?>',
'<?= $row['Gender'] ?>',
'<?= $row['CustomerType'] ?>',
'<?= $row['CustomerAddress'] ?>',
'<?= $row['Nationality'] ?>',
'<?= $row['StayCount'] ?>',
'<?= $row['TotalSpent'] ?>'
)">

                <td><?= $row['CustomerId'] ?></td>

                <td><?= $row['CustomerName'] ?></td>

                <td><?= $row['PhoneNumber'] ?></td>

                <td><?= $row['Email'] ?></td>

                <td>
    <?= $row['StayCount'] ?>
</td>

<td>
    <?= number_format($row['TotalSpent']) ?>đ
</td>

<td>

<?php

if($row['StayCount'] >= 10){

    echo '<span class="vip-badge">VIP</span>';
}
else if($row['StayCount'] >= 5){

    echo '<span class="regular-badge">
            Regular
          </span>';
}
else{

    echo '<span class="new-badge">
            New
          </span>';
}

?>

</td>

                <td class="action">
<button
class="edit"
onclick="
event.stopPropagation();

openEditCustomer(
'<?= $row['CustomerId'] ?>',
'<?= $row['CustomerName'] ?>',
'<?= $row['PhoneNumber'] ?>',
'<?= $row['Email'] ?>',
'<?= $row['CCCD'] ?>',
'<?= $row['Birthday'] ?>',
'<?= $row['Gender'] ?>',
'<?= $row['CustomerAddress'] ?>',
'<?= $row['Nationality'] ?>',
'<?= $row['CustomerType'] ?>'
);
"
>
Sửa
</button>

<button
    class="delete"
    onclick="
        event.stopPropagation();
        openDeleteModal(
            '<?= $row['CustomerId'] ?>'
        )
    "
>
    Xóa
</button>

                </td>

            </tr>

            <?php endwhile; ?>

        <?php else: ?>

            <tr>

                <td colspan="6">
                    Không tìm thấy khách hàng
                </td>

            </tr>

        <?php endif; ?>

    </table>

</div>

<!-- MODAL -->
<div id="customerModal" class="modal">

    <div class="modal-content customer-modal">

        <!-- HEADER -->
        <div class="modal-header">

            <h3>
                Thêm khách hàng
            </h3>

            <span
                class="close-btn"
                onclick="toggleForm()"
            >
                ×
            </span>

        </div>

        <!-- FORM -->
        <form class="form-grid">

            <!-- MÃ KH -->
            <div class="form-group">

                <label>Mã khách hàng</label>

               <input
    type="text"
    name="CustomerId"
    value="<?= $nextCustomerId ?>"
    readonly
>

            </div>

            <!-- HỌ TÊN -->
            <div class="form-group">

                <label>Họ tên</label>

                <input type="text">

            </div>

            <!-- CCCD -->
            <div class="form-group">

                <label>CCCD</label>

                <input type="text">

            </div>

            <!-- SDT -->
            <div class="form-group">

                <label>Số điện thoại</label>

                <input type="text">

            </div>

            <!-- EMAIL -->
            <div class="form-group">

                <label>Email</label>

                <input type="email">

            </div>

            <!-- NGÀY SINH -->
            <div class="form-group">

                <label>Ngày sinh</label>

                <input type="date">

            </div>

            <!-- GIỚI TÍNH -->
            <div class="form-group">

                <label>Giới tính</label>

                <select>

                    <option>
                        Nam
                    </option>

                    <option>
                        Nữ
                    </option>

                </select>

            </div>

            <!-- QUỐC TỊCH -->
            <div class="form-group">

                <label>Quốc tịch</label>

                <select>

                    <option>
                        Việt Nam
                    </option>

                    <option>
                        Hàn Quốc
                    </option>

                    <option>
                        Mỹ
                    </option>

                </select>

            </div>

            <div class="form-group full">

                <label>Địa chỉ</label>

                <input type="text">

            </div>

            <div class="form-group">

                <label>Hạng khách hàng</label>

                <input
                    type="text"
                    value="New"
                    readonly
                >

            </div>

            <div class="form-group">

                <label>Số lần lưu trú</label>

                <input
                    type="number"
                    value="0"
                    readonly
                >

            </div>

            <!-- CHI TIÊU -->
            <div class="form-group">

                <label>Tổng chi tiêu</label>

                <input
                    type="text"
                    value="0đ"
                    readonly
                >

            </div>

            <!-- NGÀY TẠO -->
            <div class="form-group">

                <label>Ngày tạo</label>

                <input type="date">

            </div>

            <!-- BUTTON -->
            <button
                type="submit"
                class="submit-btn full"
            >

                Thêm khách hàng

            </form>

    </div>

</div>

     
<script>

function toggleForm(){

    const modal =
    document.getElementById("customerModal");

    modal.classList.toggle("show");

}

</script>
<!-- MODAL -->
function toggleForm(){

    const modal = document.getElementById("customerModal");

    modal.classList.toggle("show");

}

</script>
<!-- DELETE MODAL -->
<div id="deleteModal" class="delete-modal">
<input
type="hidden"
id="deleteCustomerId"
name="CustomerId"
>
    <div class="delete-box">

        <h3>Xóa khách hàng</h3>

        <p>Bạn chắc chắn muốn xóa khách hàng này?</p>

        <div class="delete-actions">

            <button class="cancel-btn"
                    onclick="closeDeleteModal()">
                Hủy
            </button>

           <form
    action="delete_customer.php"
    method="POST"
>

    <input
        type="hidden"
        id="deleteCustomerId"
        name="CustomerId"
    >
<form
    action="delete_customer.php"
    method="POST"
>

    <input
        type="hidden"
        id="deleteCustomerId"
        name="CustomerId"
    >

    <button
        type="submit"
        class="confirm-btn"
    >
        Xóa
    </button>

</form>

</form>

        </div>

    </div>

</div>
<script>

function openDeleteModal(id){

    document
        .getElementById("deleteCustomerId")
        .value=id;

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
<div id="detailCustomerModal" class="modal">

    <div class="modal-content customer-modal">

        <div class="modal-header">

            <h3>Chi tiết khách hàng</h3>

            <span
                class="close-btn"
                onclick="closeDetailCustomer()"
            >×</span>

        </div>

        <div class="form-grid">

            <div class="form-group">
                <label>Mã KH</label>
                <input id="dCustomerId" readonly>
            </div>

            <div class="form-group">
                <label>Họ tên</label>
                <input id="dCustomerName" readonly>
            </div>

            <div class="form-group">
                <label>SĐT</label>
                <input id="dPhone" readonly>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input id="dEmail" readonly>
            </div>

            <div class="form-group">
                <label>CCCD</label>
                <input id="dCCCD" readonly>
            </div>

            <div class="form-group">
                <label>Ngày sinh</label>
                <input id="dBirthday" readonly>
            </div>

            <div class="form-group">
                <label>Giới tính</label>
                <input id="dGender" readonly>
            </div>

            <div class="form-group">
                <label>Hạng KH</label>
                <input id="dType" readonly>
            </div>

            <div class="form-group full">
                <label>Địa chỉ</label>
                <input id="dAddress" readonly>
            </div>

            <div class="form-group">
                <label>Quốc tịch</label>
                <input id="dNationality" readonly>
            </div>

            <div class="form-group">
                <label>Số lần lưu trú</label>
                <input id="dStayCount" readonly>
            </div>

            <div class="form-group">
                <label>Tổng chi tiêu</label>
                <input id="dTotalSpent" readonly>
            </div>

        </div>

    </div>

</div>
<div id="editCustomerModal" class="modal">

    <div class="modal-content customer-modal">

        <div class="modal-header">

            <h3>Chỉnh sửa khách hàng</h3>

            <span
                class="close-btn"
                onclick="closeEditCustomer()"
            >
                ×
            </span>

        </div>

        <form
            action="update_customer.php"
            method="POST"
        >

            <div class="form-grid">

                <div class="form-group">

                    <label>Mã khách hàng</label>

                    <input
                        id="eCustomerId"
                        name="CustomerId"
                        readonly
                    >

                </div>

                <div class="form-group">

                    <label>Họ tên</label>

                    <input
                        id="eCustomerName"
                        name="CustomerName"
                    >

                </div>

                <div class="form-group">

                    <label>SĐT</label>

                    <input
                        id="ePhone"
                        name="PhoneNumber"
                    >

                </div>

                <div class="form-group">

                    <label>Email</label>

                    <input
                        id="eEmail"
                        name="Email"
                    >

                </div>

                <div class="form-group">

                    <label>CCCD</label>

                    <input
                        id="eCCCD"
                        name="CCCD"
                    >

                </div>

                <div class="form-group">

                    <label>Ngày sinh</label>

                    <input
                        type="date"
                        id="eBirthday"
                        name="Birthday"
                    >

                </div>

                <div class="form-group">

                    <label>Giới tính</label>

                    <select
                        id="eGender"
                        name="Gender"
                    >
                        <option>Nam</option>
                        <option>Nữ</option>
                    </select>

                </div>

                <div class="form-group">

                    <label>Loại khách</label>

                    <select
                        id="eType"
                        name="CustomerType"
                    >
                        <option>VIP</option>
                        <option>Regular</option>
                        <option>New</option>
                    </select>

                </div>

            </div>

            <button
                type="submit"
                class="save-btn"
            >
                Lưu thay đổi
            </button>

        </form>

    </div>

</div>
<div id="editCustomerModal" class="modal">

    <div class="modal-content customer-modal">

        <div class="modal-header">

            <h3>Chỉnh sửa khách hàng</h3>

            <span
                class="close-btn"
                onclick="closeEditCustomer()"
            >
                ×
            </span>

        </div>

        <form
            action="update_customer.php"
            method="POST"
            class="form-grid"
        >

            <div class="form-group">
                <label>Mã khách hàng</label>

                <input
                    id="eCustomerId"
                    name="CustomerId"
                    readonly
                >
            </div>

            <div class="form-group">
                <label>Họ tên</label>

                <input
                    id="eCustomerName"
                    name="CustomerName"
                >
            </div>

            <div class="form-group">
                <label>Số điện thoại</label>

                <input
                    id="ePhone"
                    name="PhoneNumber"
                >
            </div>

            <div class="form-group">
                <label>Email</label>

                <input
                    id="eEmail"
                    name="Email"
                >
            </div>

            <div class="form-group">
                <label>CCCD</label>

                <input
                    id="eCCCD"
                    name="CCCD"
                >
            </div>

            <div class="form-group">
                <label>Ngày sinh</label>

                <input
                    type="date"
                    id="eBirthday"
                    name="Birthday"
                >
            </div>

            <div class="form-group">
                <label>Giới tính</label>

                <select
                    id="eGender"
                    name="Gender"
                >
                    <option>Nam</option>
                    <option>Nữ</option>
                </select>
            </div>

            <div class="form-group">
                <label>Quốc tịch</label>

                <input
                    id="eNationality"
                    name="Nationality"
                >
            </div>

            <div class="form-group full">
                <label>Địa chỉ</label>

                <input
                    id="eAddress"
                    name="CustomerAddress"
                >
            </div>

            <button
                type="submit"
                class="submit-btn full"
            >
                Lưu thay đổi
            </button>

        </form>

    </div>

</div>
<div id="successModal" class="modal">

    <div class="success-modal">

        <div class="success-icon">
            ✓
        </div>

        <h3>Xóa thành công</h3>

        <p>
            Khách hàng đã được xóa khỏi hệ thống.
        </p>

        <button
            onclick="closeSuccessModal()"
            class="success-btn"
        >
            OK
        </button>

    </div>

</div>
<div id="editCustomerModal" class="modal">

    <div class="modal-content customer-modal">

        <div class="modal-header">

            <h3>Chỉnh sửa khách hàng</h3>

            <span
                class="close-btn"
                onclick="closeEditCustomer()"
            >
                ×
            </span>

        </div>

        <form
            action="update_customer.php"
            method="POST"
            class="form-grid"
        >

            <div class="form-group">

                <label>Mã KH</label>

                <input
                    id="eCustomerId"
                    name="CustomerId"
                    readonly
                >

            </div>

            <div class="form-group">

                <label>Họ tên</label>

                <input
                    id="eCustomerName"
                    name="CustomerName"
                >

            </div>

            <div class="form-group">

                <label>SĐT</label>

                <input
                    id="ePhone"
                    name="PhoneNumber"
                >

            </div>

            <div class="form-group">

                <label>Email</label>

                <input
                    id="eEmail"
                    name="Email"
                >

            </div>

            <div class="form-group">

                <label>CCCD</label>

                <input
                    id="eCCCD"
                    name="CCCD"
                >

            </div>

            <div class="form-group">

                <label>Ngày sinh</label>

                <input
                    type="date"
                    id="eBirthday"
                    name="Birthday"
                >

            </div>

            <div class="form-group">

                <label>Giới tính</label>

                <select
                    id="eGender"
                    name="Gender"
                >
                    <option>Nam</option>
                    <option>Nữ</option>
                </select>

            </div>

            <div class="form-group">

                <label>Loại KH</label>

                <select
                    id="eType"
                    name="CustomerType"
                >
                    <option>VIP</option>
                    <option>Regular</option>
                    <option>New</option>
                </select>

            </div>

            <div class="form-group full">

                <label>Địa chỉ</label>

                <input
                    id="eAddress"
                    name="CustomerAddress"
                >

            </div>

            <div class="form-group full">

                <label>Quốc tịch</label>

                <input
                    id="eNationality"
                    name="Nationality"
                >

            </div>

            <button
                type="submit"
                class="submit-btn full"
            >
                Lưu thay đổi
            </button>

        </form>

    </div>

</div>
<div id="updateSuccessModal" class="modal">

    <div class="success-modal">

        <div class="success-icon">
            ✓
        </div>

        <h3>Cập nhật thành công</h3>

        <p>
            Thông tin khách hàng đã được cập nhật.
        </p>

        <button
            onclick="closeUpdateSuccessModal()"
            class="success-btn"
        >
            OK
        </button>

    </div>

</div>
</body>
<script>

function viewCustomer(
id,name,phone,email,
cccd,birthday,gender,
type,address,nationality,
stay,total
){

    document.getElementById("dCustomerId").value=id;
    document.getElementById("dCustomerName").value=name;
    document.getElementById("dPhone").value=phone;
    document.getElementById("dEmail").value=email;
    document.getElementById("dCCCD").value=cccd;
    document.getElementById("dBirthday").value=birthday;
    document.getElementById("dGender").value=gender;
    document.getElementById("dType").value=type;
    document.getElementById("dAddress").value=address;
    document.getElementById("dNationality").value=nationality;
    document.getElementById("dStayCount").value=stay;
    document.getElementById("dTotalSpent").value=total;

    document
        .getElementById("detailCustomerModal")
        .classList.add("show");
}

function closeDetailCustomer(){

    document
        .getElementById("detailCustomerModal")
        .classList.remove("show");
}

</script>
<script>

function openEditCustomer(
id,
name,
phone,
email,
cccd,
birthday,
gender,
address,
nationality,
type
){

    eCustomerId.value=id;
    eCustomerName.value=name;
    ePhone.value=phone;
    eEmail.value=email;
    eCCCD.value=cccd;
    eBirthday.value=birthday;
    eGender.value=gender;
    eAddress.value=address;
    eNationality.value=nationality;
    eType.value=type;

    document
    .getElementById("editCustomerModal")
    .classList.add("show");
}

function closeEditCustomer(){

    document
    .getElementById("editCustomerModal")
    .classList.remove("show");
}
function closeSuccessModal(){

    window.location.href =
        "customer_list.php";

}
function closeEditCustomer(){

    document
    .getElementById("editCustomerModal")
    .classList.remove("show");

}
function copyDataToEditForm(){

    eCustomerId.value =
        dCustomerId.value;

    eCustomerName.value =
        dCustomerName.value;

    ePhone.value =
        dPhone.value;

    eEmail.value =
        dEmail.value;

    eCCCD.value =
        dCCCD.value;

    eBirthday.value =
        dBirthday.value;

    eGender.value =
        dGender.value;

    eType.value =
        dType.value;

    closeDetailCustomer();

    openEditCustomer();
}
function openEditCustomer(){

    document.getElementById("eCustomerId").value =
        document.getElementById("dCustomerId").value;

    document.getElementById("eCustomerName").value =
        document.getElementById("dCustomerName").value;

    document.getElementById("ePhone").value =
        document.getElementById("dPhone").value;

    document.getElementById("eEmail").value =
        document.getElementById("dEmail").value;

    document.getElementById("eCCCD").value =
        document.getElementById("dCCCD").value;

    document.getElementById("eBirthday").value =
        document.getElementById("dBirthday").value;

    document.getElementById("eGender").value =
        document.getElementById("dGender").value;

    document.getElementById("eAddress").value =
        document.getElementById("dAddress").value;

    document.getElementById("eNationality").value =
        document.getElementById("dNationality").value;

    closeDetailCustomer();

    document
        .getElementById("editCustomerModal")
        .classList.add("show");
}

function closeEditCustomer(){

    document
        .getElementById("editCustomerModal")
        .classList.remove("show");
}
function closeUpdateSuccessModal(){

    window.location.href =
        "customer_list.php";

}
<?php if($updated): ?>

document
.getElementById("updateSuccessModal")
.classList.add("show");

<?php endif; ?>
<?php if($deleted): ?>

document
.getElementById("successModal")
.classList.add("show");

<?php endif; ?>
</script>

</html>