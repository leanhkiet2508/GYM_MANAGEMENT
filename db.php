<?php
    $conn = new mysqli('localhost', 'root', '', 'GymFace');
    session_start();

    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Hàm kiểm tra đăng nhập 
    function checkLogin() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }
    }
?>