<?php
session_start();

// chỉ admin được vào
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "Bạn không có quyền!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tạo tài khoản</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../create_user.css">

    <!-- ICON -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>

<div class="form-container">

    <h2>Tạo tài khoản mới</h2>

   <form id="createForm">

        <div class="input-group">
            <i data-lucide="user"></i>
            <input 
    type="email" 
    name="username"
    placeholder="Nhập Gmail"
    pattern=".+@gmail\.com$"
    required
>
        </div>

        <div class="input-group">
            <i data-lucide="lock"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <div class="input-group">
            <i data-lucide="users"></i>
            <select name="role">
                <option value="manager">Manager</option>
                <option value="staff">Staff</option>
            </select>
        </div>

        <button type="submit">Tạo tài khoản</button>

    </form>

</div>

<!-- KÍCH HOẠT ICON -->
<script>
  lucide.createIcons();
</script>
<script>

document.getElementById("createForm").addEventListener("submit", function(e){

    e.preventDefault();

    let formData = new FormData(this);

    fetch("save_user.php", {
        method: "POST",
        body: formData
    })

    .then(response => response.text())

    .then(data => {

        document.getElementById("popupMessage").innerText = data;

        document.getElementById("successBox").style.display = "flex";

        document.getElementById("createForm").reset();

    });

});

function closePopup(){

    document.getElementById("successBox").style.display = "none";

}

</script>
<div id="successBox" class="popup">

    <div class="popup-content">

        <h3 id="popupMessage"></h3>

        <button onclick="closePopup()">OK</button>

    </div>

</div>
</body>
</html>