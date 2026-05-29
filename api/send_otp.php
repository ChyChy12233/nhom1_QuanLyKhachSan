<?php

session_start();

$conn = mysqli_connect("localhost", "root", "", "hotel");

$email = $_POST['email'];


// kiểm tra email tồn tại
$sql = "SELECT * FROM staff WHERE Username='$email'";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0){

    // tạo OTP
    $otp = rand(100000,999999);

    // lưu session
    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;

    echo "
    <script>

        alert('OTP của bạn là: $otp');

        window.location.href='verify_otp.php';

    </script>
    ";

}else{

    echo "
    <script>

        alert('Email không tồn tại');

        window.history.back();

    </script>
    ";

}
?>