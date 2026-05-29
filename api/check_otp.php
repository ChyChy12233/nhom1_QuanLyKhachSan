<?php

session_start();

$user_otp = $_POST['otp'];

if($user_otp == $_SESSION['otp']){

    header("Location: reset_password.php");

}else{

    echo "
    <script>

        alert('OTP sai');

        window.history.back();

    </script>
    ";

}

?>