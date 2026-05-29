<!DOCTYPE html>
<html>
<head>
    <title>Đổi mật khẩu</title>
</head>
<body>

<h2>Đổi mật khẩu mới</h2>

<form action="update_password.php" method="POST">

    <input type="password" 
           name="new_password" 
           placeholder="Mật khẩu mới" 
           required>

    <br><br>

    <input type="password" 
           name="confirm_password" 
           placeholder="Xác nhận mật khẩu" 
           required>

    <br><br>

    <button type="submit">Đổi mật khẩu</button>

</form>

</body>
</html>