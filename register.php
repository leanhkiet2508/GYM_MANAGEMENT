<?php
    include 'db.php'; 

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password']; 
        $mobile = $_POST['mobile'];
        $address = $_POST['address'];

        $sql = "INSERT INTO `User` (name, email, password, mobile, address, role) 
                VALUES ('$name', '$email', '$password', '$mobile', '$address', 'member')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>
                    alert('Đăng ký thành công! Hãy đăng nhập.');
                    window.location.href = 'login.php'; 
                </script>";
            exit(); 
        } else {
            echo "Lỗi: " . $conn->error;
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Đăng ký GymFace</title>
        <link rel="stylesheet" href="style.css"> 
    </head>
    <body>
        <div class="container" style="max-width: 400px; margin-top:50px">
            <h2 style="text-align:center">Đăng ký thành viên</h2>
            <form method="POST" action="">
                <input type="text" name="name" placeholder="Họ tên" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Mật khẩu" required>
                <input type="text" name="mobile" placeholder="Số điện thoại" required>
                <input type="text" name="address" placeholder="Địa chỉ">
                <button type="submit">Đăng ký ngay</button>
            </form>
            <p style="text-align:center">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
        </div>
    </body>
</html>