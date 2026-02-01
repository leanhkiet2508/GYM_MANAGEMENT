<?php
include 'db.php';
// Kiểm tra nếu đã đăng nhập rồi thì đá về trang chủ luôn
if(isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit;
}

if(isset($_POST['login'])){
    $u = $_POST['email'];
    $p = $_POST['pass'];
    
    // Tìm trong bảng User (Nhớ dấu backtick ` `)
    $sql = "SELECT * FROM `User` WHERE email='$u' AND password='$p'";
    $res = $conn->query($sql);
    
    if($res->num_rows > 0){
        $row = $res->fetch_assoc();
        
        // Lưu session
        $_SESSION['user_id'] = $row['user_id']; 
        $_SESSION['uname'] = $row['name'];
        $_SESSION['role'] = $row['role']; 
        
        // Điều hướng
        if($row['role'] == 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        echo "<script>alert('Sai email hoặc mật khẩu!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 400px; margin-top: 50px;">
        <h2 style="text-align:center">ĐĂNG NHẬP GYMFACE</h2>
        <form method="POST">
            <label>Email:</label>
            <input type="text" name="email" required placeholder="Nhập email...">
            
            <label>Mật khẩu:</label>
            <input type="password" name="pass" required placeholder="******">
            
            <button name="login" style="margin-top:10px">Đăng nhập</button>
        </form>
        
        <p style="text-align:center; margin-top:15px">
            Chưa có tài khoản? <a href="register.php" style="color:blue">Đăng ký ngay</a>
        </p>
        
        <p style="text-align:center; font-size:14px">
            <a href="reset_password.php" style="color:gray">Quên mật khẩu?</a>
        </p>
    </div>
</body>
</html>