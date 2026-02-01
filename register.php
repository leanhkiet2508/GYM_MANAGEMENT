<?php
include 'db.php';

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['password']; // Thực tế nên dùng password_hash()
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // 1. Kiểm tra Email tồn tại (UC 6.2.2)
    $check = $conn->query("SELECT * FROM Member WHERE email='$email'");
    if ($check->num_rows > 0) {
        $error = "Email này đã được sử dụng!";
    } else {
        // 2. Tạo tài khoản (UC 6.2.1)
        $sql = "INSERT INTO Member (name, email, password, mobile, address) 
                VALUES ('$name', '$email', '$pass', '$phone', '$address')";
        
        if ($conn->query($sql)) {
            echo "<script>alert('Đăng ký thành công! Hãy đăng nhập.'); window.location='login.php';</script>";
        } else {
            $error = "Lỗi: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Đăng ký thành viên</title></head>
<body>
    <h2>Đăng ký hội viên GymFace</h2>
    <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
    
    <form method="POST">
        Họ tên: <input type="text" name="name" required><br>
        Email: <input type="email" name="email" required><br>
        Mật khẩu: <input type="password" name="password" required><br>
        SĐT: <input type="text" name="phone"><br>
        Địa chỉ: <input type="text" name="address"><br>
        <button type="submit" name="register">Đăng ký ngay</button>
    </form>
    <a href="login.php">Đã có tài khoản? Đăng nhập</a>
</body>
</html>