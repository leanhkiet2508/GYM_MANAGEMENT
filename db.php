<?php
// db.php - Kết nối CSDL
$conn = new mysqli('localhost', 'root', '', 'GymFace');
session_start(); // Khởi động session cho toàn bộ web

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Hàm kiểm tra đăng nhập (Dùng chung cho tiện)
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}
?>