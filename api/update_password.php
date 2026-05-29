<?php

session_start();

$conn = mysqli_connect("localhost", "root", "", "hotel");

$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

$email = $_SESSION['email'];


// kiểm tra xác nhận mật khẩu
if($new_password != $confirm_password){

    echo "
    <script>

        alert('Mật khẩu xác nhận không đúng');

        window.history.back();

    </script>
    ";

    exit();
}
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// update password
$sql = "UPDATE staff 
        SET Password='$hashed_password'
        WHERE Username='$email'";


if(mysqli_query($conn, $sql)){

    echo "
    <script>

        alert('Đổi mật khẩu thành công');

        window.location.href='login.php';

    </script>
    ";

}else{

    echo "
    <script>

        alert('Lỗi cập nhật mật khẩu');

        window.history.back();

    </script>
    ";

}
?>