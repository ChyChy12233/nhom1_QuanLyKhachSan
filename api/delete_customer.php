<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "hotel"
);

$id = $_POST['CustomerId'];

mysqli_query(
    $conn,
    "DELETE FROM customer
     WHERE CustomerId='$id'"
);

header(
    "Location: customer_list.php?deleted=1"
);

exit();