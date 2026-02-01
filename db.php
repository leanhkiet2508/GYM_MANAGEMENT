<?php
// Đây là cầu nối Database
$conn = new mysqli('localhost', 'root', '', 'GymFace');
session_start(); // Bắt đầu phiên làm việc (Thay cho biến currentMember)

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>