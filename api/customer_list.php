<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

$keyword = trim($_GET['keyword'] ?? '');
$type    = $_GET['type'] ?? '';

$sql    = "SELECT * FROM customer WHERE 1=1";
$params = [];
$types  = '';

if ($keyword !== '') {
    $sql   .= " AND (CustomerName LIKE ? OR PhoneNumber LIKE ? OR Email LIKE ?)";
    $like   = '%' . $keyword . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types   .= 'sss';
}

if ($type !== '') {
    $sql     .= " AND CustomerType = ?";
    $params[] = $type;
    $types   .= 's';
}

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
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
                value="<?= e($keyword) ?>"
            >

            <select name="type">

                <option value="">
                    -- Tất cả loại --
                </option>

                <option
                    value="Nội địa"
                    <?= ($type=='Nội địa')?'selected':'' ?>
                >
                    Nội địa
                </option>

                <option
                    value="Nước ngoài"
                    <?= ($type=='Nước ngoài')?'selected':'' ?>
                >
                    Nước ngoài
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
           <tr>

    <th>ID</th>

    <th>Tên</th>

    <th>SĐT</th>

    <th>Email</th>

    <th>Số lần lưu trú</th>

    <th>Tổng chi tiêu</th>

    <th>Hạng KH</th>

    <th>Action</th>

</tr>
        </tr>

        <?php if (mysqli_num_rows($result) > 0): ?>

            <?php while($row = mysqli_fetch_assoc($result)): ?>

            <tr>

                <td><?= e($row['CustomerId']) ?></td>

                <td><?= e($row['CustomerName']) ?></td>

                <td><?= e($row['PhoneNumber']) ?></td>

                <td><?= e($row['Email']) ?></td>

                <td>
    <?= e($row['StayCount']) ?>
</td>

<td>
    <?= number_format((float)$row['TotalSpent']) ?>đ
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

                    <a
                        href="edit_customer.php?id=<?= e($row['CustomerId']) ?>"
                        class="edit"
                    >
                        Sửa
                    </a>

                   <button
    class="delete"
    onclick="openDeleteModal('<?= e($row['CustomerId']) ?>')"
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

    <div class="modal-content">

        <div class="modal-header">

            <h3>Thêm khách hàng</h3>

            <span
                class="close-btn"
                onclick="toggleForm()"
            >
                ×
            </span>

        </div>

        <form action="save_customer.php" method="POST">

            <div class="form-grid">

                <div class="form-group">
                    <label>Họ tên</label>
                    <input type="text" name="name">
                </div>

                <div class="form-group">
                    <label>CCCD</label>
                    <input type="text" name="cccd">
                </div>

                <div class="form-group">
                    <label>SĐT</label>
                    <input type="text" name="phone">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email">
                </div>

                <div class="form-group">
                    <label>Quốc tịch</label>

                    <select name="nationality">
                        <option>Việt Nam</option>
                        <option>Hàn Quốc</option>
                        <option>Nhật Bản</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Phân loại KH</label>

                    <select name="type">
                        <option>Thường</option>
                        <option>VIP</option>
                    </select>
                </div>

                <button type="submit" class="submit-btn">
                    Lưu khách hàng
                </button>

            </div>

        </form>

    </div>

</div>

<script>

function toggleForm(){

    const modal = document.getElementById("customerModal");

    modal.classList.toggle("show");

}

</script>
<!-- DELETE MODAL -->
<div id="deleteModal" class="delete-modal">

    <div class="delete-box">

        <h3>Xóa khách hàng</h3>

        <p>Bạn chắc chắn muốn xóa khách hàng này?</p>

        <form method="POST" action="delete_customer.php">
            <input type="hidden" id="deleteCustomerId" name="id" value="">

            <div class="delete-actions">

                <button type="button" class="cancel-btn"
                        onclick="closeDeleteModal()">
                    Hủy
                </button>

                <button type="submit" class="confirm-btn">
                    Xóa
                </button>

            </div>
        </form>

    </div>

</div>
<script>

function openDeleteModal(id){

    document.getElementById("deleteCustomerId").value = id;

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
