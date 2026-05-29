<?php

$conn = mysqli_connect("localhost", "root", "", "hotel");

$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];

$sql = "INSERT INTO staff(username, password, role)
VALUES('$username', '$password', '$role')";

if(mysqli_query($conn, $sql)){

    echo "Tạo tài khoản thành công";

}else{

    echo "Tạo tài khoản thất bại";

}

?>