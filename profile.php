<?php
include 'db.php';
if (!isset($_SESSION['uid'])) { header("Location: login.php"); exit; }

$uid = $_SESSION['uid'];
$msg = "";

// 1. XỬ LÝ CẬP NHẬT
if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $phone = $_POST['mobile'];
    $addr = $_POST['address'];
    $h = $_POST['height'];
    $w = $_POST['weight'];

    $sql = "UPDATE Member SET name='$name', mobile='$phone', address='$addr', height=$h, weight=$w WHERE member_id=$uid";
    
    if ($conn->query($sql)) {
        $_SESSION['uname'] = $name; // Cập nhật lại tên trên Session
        $msg = "<p style='color:green'>Cập nhật thành công!</p>";
    } else {
        $msg = "<p style='color:red'>Lỗi: " . $conn->error . "</p>";
    }
}

// 2. LẤY THÔNG TIN HIỆN TẠI
$info = $conn->query("SELECT * FROM Member WHERE member_id=$uid")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head><title>Hồ sơ cá nhân</title></head>
<body>
    <a href="index.php">← Về trang chủ</a>
    <h2>Cập nhật hồ sơ</h2>
    <?php echo $msg; ?>

    <form method="POST" style="width: 300px;">
        <label>Email (Không thể sửa):</label><br>
        <input type="text" value="<?php echo $info['email']; ?>" disabled style="background:#eee; width:100%"><br><br>

        <label>Họ tên:</label><br>
        <input type="text" name="name" value="<?php echo $info['name']; ?>" required style="width:100%"><br><br>

        <label>Số điện thoại:</label><br>
        <input type="text" name="mobile" value="<?php echo $info['mobile']; ?>" style="width:100%"><br><br>

        <label>Địa chỉ:</label><br>
        <input type="text" name="address" value="<?php echo $info['address']; ?>" style="width:100%"><br><br>

        <label>Chiều cao (cm):</label><br>
        <input type="number" name="height" value="<?php echo $info['height']; ?>" step="0.1"><br><br>

        <label>Cân nặng (kg):</label><br>
        <input type="number" name="weight" value="<?php echo $info['weight']; ?>" step="0.1"><br><br>

        <button type="submit" name="update_profile">Lưu thay đổi</button>
    </form>
</body>
</html>