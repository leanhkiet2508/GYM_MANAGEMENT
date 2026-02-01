<?php
include 'db.php';

// 1. CHECK ADMIN
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$uid = $_SESSION['user_id'];

// Lấy role từ bảng User (Nhớ dùng dấu ` ` bao quanh User)
$check = $conn->query("SELECT role FROM `User` WHERE user_id=$uid")->fetch_assoc();

if (!$check || $check['role'] !== 'admin') { 
    die("BẠN KHÔNG PHẢI ADMIN! <a href='index.php'>Về trang chủ</a>"); 
}

// 2. XỬ LÝ LOGIC

// A. Service: Thêm & Xóa
if (isset($_POST['add_service'])) {
    $n=$_POST['name']; $c=$_POST['code']; $p=$_POST['price']; $cat=$_POST['cat_id'];
    // Thêm các cột mặc định để tránh lỗi thiếu field
    $conn->query("INSERT INTO Service_package (category_id, code, name, price, status, duration, detail) 
                  VALUES ($cat, '$c', '$n', $p, 'Active', 30, 'Mô tả mặc định')");
    header("Location: admin.php");
}
if (isset($_POST['delete_service'])) {
    $id = $_POST['del_id'];
    $conn->query("DELETE FROM Service_package WHERE service_package_id=$id");
    header("Location: admin.php");
}

// B. Order: Duyệt & Hủy
if(isset($_POST['approve_order'])) {
    $conn->query("UPDATE `Order` SET status='Paid' WHERE order_id=".$_POST['oid']);
    header("Location: admin.php");
}
if(isset($_POST['cancel_order'])) {
    $conn->query("UPDATE `Order` SET status='Cancelled' WHERE order_id=".$_POST['oid']);
    header("Location: admin.php");
}

// C. User: Xóa hội viên (Đã sửa tên bảng)
if(isset($_POST['delete_member'])) {
    $mid = $_POST['mid'];
    // Xóa dữ liệu liên quan
    $conn->query("DELETE FROM Order_detail WHERE order_id IN (SELECT order_id FROM `Order` WHERE user_id=$mid)");
    $conn->query("DELETE FROM `Order` WHERE user_id=$mid");
    $conn->query("DELETE FROM Cart WHERE user_id=$mid");
    // Xóa User
    $conn->query("DELETE FROM `User` WHERE user_id=$mid");
    header("Location: admin.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel GymFace</title>
    <link rel="stylesheet" href="style.css"> <style>
        .box { border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; background: white; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #333; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h1>TRANG QUẢN TRỊ VIÊN</h1>
        <a href="index.php" style="color:red; font-weight:bold">← Về trang chủ</a> | 
        <a href="logout.php">Đăng xuất</a>

        <div class="box">
            <h2>1. Quản lý Dịch vụ</h2>
            <details>
                <summary style="cursor:pointer; color:blue; font-weight:bold">+ Thêm gói mới</summary>
                <form method="POST" style="margin-top:10px">
                    <input type="text" name="name" placeholder="Tên gói" required>
                    <input type="text" name="code" placeholder="Mã (SV01)" required>
                    <input type="number" name="price" placeholder="Giá" required>
                    <select name="cat_id" style="padding:10px">
                        <option value="1">Gói Tập</option><option value="2">PT</option><option value="3">SPBS</option>
                    </select>
                    <button name="add_service">Thêm</button>
                </form>
            </details>
            <br>
            <table>
                <tr><th>Mã</th><th>Tên</th><th>Giá</th><th>Xóa</th></tr>
                <?php 
                $res = $conn->query("SELECT * FROM Service_package ORDER BY service_package_id DESC");
                while($r = $res->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $r['code']; ?></td>
                    <td><?php echo $r['name']; ?></td>
                    <td><?php echo number_format($r['price']); ?></td>
                    <td>
                        <form method="POST" onsubmit="return confirm('Xóa gói này?');">
                            <input type="hidden" name="del_id" value="<?php echo $r['service_package_id']; ?>">
                            <button name="delete_service" style="background:red; color:white; padding:5px 10px">Xóa</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div class="box">
            <h2>2. Quản lý Đơn hàng</h2>
            <table>
                <tr><th>ID</th><th>Khách</th><th>Tổng</th><th>Trạng thái</th><th>Thao tác</th></tr>
                <?php 
                // JOIN với bảng User thay vì Member
                $res = $conn->query("SELECT o.*, u.name FROM `Order` o JOIN `User` u ON o.user_id = u.user_id ORDER BY order_id DESC");
                while($r = $res->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $r['order_id']; ?></td>
                    <td><?php echo $r['name']; ?></td>
                    <td><?php echo number_format($r['total']); ?></td>
                    <td>
                        <span style="color: <?php echo ($r['status']=='Paid')?'green':'red'; ?>">
                            <?php echo $r['status']; ?>
                        </span>
                    </td>
                    <td>
                        <?php if($r['status']=='New'): ?>
                        <form method="POST" style="display:inline">
                            <input type="hidden" name="oid" value="<?php echo $r['order_id']; ?>">
                            <button name="approve_order" style="background:green; width:auto">Duyệt</button>
                            <button name="cancel_order" style="background:red; width:auto">Hủy</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div class="box">
            <h2>3. Quản lý Hội viên (User)</h2>
            <table>
                <tr><th>ID</th><th>Họ tên</th><th>Email</th><th>Vai trò</th><th>Thao tác</th></tr>
                <?php 
                // Select từ User
                $res = $conn->query("SELECT * FROM `User`");
                while($r = $res->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $r['user_id']; ?></td>
                    <td><?php echo $r['name']; ?></td>
                    <td><?php echo $r['email']; ?></td>
                    <td>
                        <?php echo ($r['role']=='admin') ? '<b style="color:red">ADMIN</b>' : 'Member'; ?>
                    </td>
                    <td>
                        <?php if($r['role'] != 'admin'): ?>
                        <form method="POST" onsubmit="return confirm('Xóa hội viên này?');">
                            <input type="hidden" name="mid" value="<?php echo $r['user_id']; ?>">
                            <button name="delete_member" style="background:red; width:auto; padding:5px">Xóa</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>
</html>