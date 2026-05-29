<?php
$conn = mysqli_connect("localhost","root","","hotel");

$id = $_POST['CustomerId'];

$CustomerName = $_POST['CustomerName'];
$PhoneNumber = $_POST['PhoneNumber'];
$Email = $_POST['Email'];
$CCCD = $_POST['CCCD'];
$Birthday = $_POST['Birthday'];
$Gender = $_POST['Gender'];
$CustomerType = $_POST['CustomerType'];
$CustomerAddress = $_POST['CustomerAddress'];

$sql = "UPDATE customer SET 
    CustomerName='$CustomerName',
    PhoneNumber='$PhoneNumber',
    Email='$Email',
    CCCD='$CCCD',
    Birthday='$Birthday',
    Gender='$Gender',
    CustomerType='$CustomerType',
    CustomerAddress='$CustomerAddress'
WHERE CustomerId='$id'";

if (mysqli_query($conn, $sql)) {
 header(
"Location: customer_list.php?updated=1"
);

exit();
} else {
    echo "Lỗi: " . mysqli_error($conn);
}
?>