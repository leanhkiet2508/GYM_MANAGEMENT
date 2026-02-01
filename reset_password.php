<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head><title>Quên mật khẩu</title></head>
<body>
    <h2>Khôi phục mật khẩu</h2>
    <a href="login.php">Quay lại đăng nhập</a>
    <hr>
    
    <form method="POST">
        Nhập Email đã đăng ký: <input type="email" name="email" required>
        <button type="submit" name="reset">Gửi mật khẩu mới</button>
    </form>

    <?php
    if (isset($_POST['reset'])) {
        $email = $_POST['email'];
        // Kiểm tra email có tồn tại không
        $check = $conn->query("SELECT * FROM Member WHERE email='$email'");
        
        if ($check->num_rows > 0) {
            // Tạo mật khẩu ngẫu nhiên (hoặc mặc định là 123456)
            $new_pass = "123456"; 
            
            // Cập nhật vào Database
            $conn->query("UPDATE Member SET password='$new_pass' WHERE email='$email'");
            
            // Giả lập gửi mail (In ra màn hình)
            echo "<div style='background:#dff0d8; padding:15px; margin-top:10px; border:1px solid green;'>
                    <b>Gửi mail thành công! (Giả lập)</b><br>
                    Xin chào <b>$email</b>,<br>
                    Mật khẩu mới của bạn là: <b style='color:red; font-size:20px'>$new_pass</b><br>
                    Vui lòng dùng mật khẩu này để đăng nhập lại.
                  </div>";
        } else {
            echo "<p style='color:red'>Email này chưa đăng ký!</p>";
        }
    }
    ?>
</body>
</html>