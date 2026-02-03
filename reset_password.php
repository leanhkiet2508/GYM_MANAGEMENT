<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Quên mật khẩu</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="container" style="max-width: 400px; margin-top:50px">
            <h2>Khôi phục mật khẩu</h2>
            <a href="login.php">← Quay lại đăng nhập</a>
            <hr>
            
            <form method="POST">
                <label>Nhập Email đã đăng ký:</label>
                <input type="email" name="email" required placeholder="vidu@gmail.com">
                <button type="submit" name="reset">Gửi mật khẩu mới</button>
            </form>

            <?php
            if (isset($_POST['reset'])) {
                $email = $_POST['email'];
                
                // 1. Kiểm tra xem email có tồn tại không
                $check = $conn->query("SELECT * FROM `User` WHERE email='$email'");
                
                if ($check->num_rows > 0) {
                    $row = $check->fetch_assoc();

                    // 2. CHECK BẢO MẬT: Nếu là Admin thì không cho reset kiểu này
                    if ($row['role'] == 'admin') {
                        echo "<p style='color:red; font-weight:bold; margin-top:10px'>
                                LỖI: Tài khoản Quản trị viên không thể khôi phục tự động!<br>
                                Vui lòng liên hệ kỹ thuật viên hoặc sửa trực tiếp trong Database.
                            </p>";
                    } else {
                        // 3. SINH MẬT KHẨU NGẪU NHIÊN
                        // Lấy ngẫu nhiên 6 ký tự từ chuỗi số
                        $new_pass = substr(str_shuffle("0123456789"), 0, 6);
                        
                        // Cập nhật mật khẩu mới vào Database
                        $conn->query("UPDATE `User` SET password='$new_pass' WHERE email='$email'");
                        
                        // Giả lập gửi mail (Hiện ra màn hình để bạn copy đăng nhập)
                        echo "<div style='background:#dff0d8; padding:15px; margin-top:10px; border-radius:4px; border:1px solid green'>
                                <b style='color:green'>✔ Khôi phục thành công!</b><br>
                                Xin chào <b>{$row['name']}</b>,<br>
                                Hệ thống (giả lập) đã gửi mật khẩu mới về email của bạn.<br>
                                Mật khẩu mới là: <b style='color:red; font-size:24px; background:yellow'>$new_pass</b><br>
                                <a href='login.php'>Bấm vào đây để đăng nhập ngay</a>
                            </div>";
                    }
                } else {
                    echo "<p style='color:red; margin-top:10px'>Email này chưa từng đăng ký tài khoản nào!</p>";
                }
            }
            ?>
        </div>
    </body>
</html>