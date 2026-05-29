<?php

session_start();

/* XÓA SESSION */
session_unset();

session_destroy();

/* QUAY VỀ LOGIN */
header("Location: http://localhost:8081/Nhom1/api/");
exit();

?>