<?php
session_start();
session_unset();
session_destroy();
header('Location: /nhom1_QuanLyKhachSan/index.php');
exit;
