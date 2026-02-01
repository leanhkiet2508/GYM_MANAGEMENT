<?php
include 'db.php';
// SỬA LỖI: Dùng user_id cho đồng bộ với file Login
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$uid = $_SESSION['user_id'];
$msg = "";

// 1. XỬ LÝ CẬP NHẬT
if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $phone = $_POST['mobile'];
    $addr = $_POST['address'];
    $h = $_POST['height'];
    $w = $_POST['weight'];

    // SỬA LỖI: Thêm dấu ` ` và đổi User_id thành user_id
    $sql = "UPDATE `User` SET name='$name', mobile='$phone', address='$addr', height=$h, weight=$w WHERE user_id=$uid";
    
    if ($conn->query($sql)) {
        $_SESSION['uname'] = $name; // Cập nhật lại tên hiển thị
        $msg = "<p style='color:green; font-weight:bold'>Cập nhật thành công!</p>";
    } else {
        $msg = "<p style='color:red'>Lỗi: " . $conn->error . "</p>";
    }
}

// 2. LẤY THÔNG TIN HIỆN TẠI
$info = $conn->query("SELECT * FROM `User` WHERE user_id=$uid")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hồ sơ cá nhân</title>
    <link rel="stylesheet" href="style.css"> </head>
<body>
    <nav>
        <a href="index.php" class="logo">GYMFACE <span style="color:white">STORE</span></a>
        <a href="index.php">← Về trang chủ</a>
    </nav>

    <div class="container" style="max-width:500px">
        <h2>Cập nhật hồ sơ</h2>
        <?php echo $msg; ?>

        <form method="POST">
            <label>Email (Không thể sửa):</label>
            <input type="text" value="<?php echo $info['email']; ?>" disabled style="background:#eee; color:#555">

            <label>Họ tên:</label>
            <input type="text" name="name" value="<?php echo $info['name']; ?>" required>

            <label>Số điện thoại:</label>
            <input type="text" name="mobile" value="<?php echo $info['mobile']; ?>">

            <label>Địa chỉ:</label>
            <input type="text" name="address" value="<?php echo $info['address']; ?>">

            <div style="display:flex; gap:10px">
                <div style="width:50%">
                    <label>Chiều cao (cm):</label>
                    <input type="number" name="height" value="<?php echo $info['height']; ?>" step="0.1">
                </div>
                <div style="width:50%">
                    <label>Cân nặng (kg):</label>
                    <input type="number" name="weight" value="<?php echo $info['weight']; ?>" step="0.1">
                </div>
            </div>

            <button type="submit" name="update_profile" style="margin-top:20px">Lưu thay đổi</button>
        </form>
    </div>
</body>
</html>