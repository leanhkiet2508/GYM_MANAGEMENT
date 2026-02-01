<?php
include 'db.php';
if(isset($_POST['login'])){
    $u = $_POST['email'];
    $p = $_POST['pass'];
    
    // Tìm trong bảng Member
    $sql = "SELECT * FROM Member WHERE email='$u' AND password='$p'";
    $res = $conn->query($sql);
    
    if($res->num_rows > 0){
        $row = $res->fetch_assoc();
        $_SESSION['uid'] = $row['member_id'];
        $_SESSION['uname'] = $row['name'];
        
        // --- THÊM DÒNG NÀY ---
        $_SESSION['role'] = $row['role']; // Lưu quyền (admin/member) vào bộ nhớ
        // ---------------------
        
        // Điều hướng thông minh: Admin vào thẳng trang Admin, Member vào trang chủ
        if($row['role'] == 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: index.php");
        }
    } else {
        echo "Sai thông tin!";
    }
}
?>
<form method="POST">
    Email: <input type="text" name="email"><br>
    Pass: <input type="password" name="pass"><br>
    <button name="login">Login</button>
</form>