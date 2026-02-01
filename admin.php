<?php
include 'db.php';

// 1. CHECK ADMIN
if (!isset($_SESSION['uid'])) { header("Location: login.php"); exit; }
$uid = $_SESSION['uid'];
$check = $conn->query("SELECT role FROM Member WHERE member_id=$uid")->fetch_assoc();
if ($check['role'] !== 'admin') { die("BẠN KHÔNG PHẢI ADMIN!"); }

// 2. XỬ LÝ LOGIC

// A. Service: Thêm & Xóa
if (isset($_POST['add_service'])) {
    $n=$_POST['name']; $c=$_POST['code']; $p=$_POST['price']; $cat=$_POST['cat_id'];
    $conn->query("INSERT INTO Service_package (category_id, code, name, price) VALUES ($cat, '$c', '$n', $p)");
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

// C. Member: Xóa hội viên (MỚI THÊM)
if(isset($_POST['delete_member'])) {
    $mid = $_POST['mid'];
    // Xóa tất cả dữ liệu liên quan đến ông này trước (tránh lỗi khóa ngoại)
    $conn->query("DELETE FROM Order_detail WHERE order_id IN (SELECT order_id FROM `Order` WHERE member_id=$mid)");
    $conn->query("DELETE FROM `Order` WHERE member_id=$mid");
    $conn->query("DELETE FROM Cart WHERE member_id=$mid");
    // Cuối cùng xóa Member
    $conn->query("DELETE FROM Member WHERE member_id=$mid");
    header("Location: admin.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel Full</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .box { border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; background: #f9f9f9; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #eee; }
        h2 { margin-top: 0; color: #333; }
    </style>
</head>
<body>
    <h1>TRANG QUẢN TRỊ VIÊN</h1>
    <a href="index.php">← Về trang chủ</a>

    <div class="box">
        <h2>1. Quản lý Dịch vụ</h2>
        <details>
            <summary style="cursor:pointer; color:blue">+ Thêm gói mới</summary>
            <form method="POST" style="margin-top:10px">
                <input type="text" name="name" placeholder="Tên gói" required>
                <input type="text" name="code" placeholder="Mã (SV01)" required>
                <input type="number" name="price" placeholder="Giá" required>
                <select name="cat_id">
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
                        <button name="delete_service" style="color:red">Xóa</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <div class="box" style="background:#eef">
        <h2>2. Quản lý Đơn hàng</h2>
        <table>
            <tr><th>ID</th><th>Khách</th><th>Tổng</th><th>Trạng thái</th><th>Thao tác</th></tr>
            <?php 
            $res = $conn->query("SELECT o.*, m.name FROM `Order` o JOIN Member m ON o.member_id = m.member_id ORDER BY order_id DESC");
            while($r = $res->fetch_assoc()): ?>
            <tr>
                <td>#<?php echo $r['order_id']; ?></td>
                <td><?php echo $r['name']; ?></td>
                <td><?php echo number_format($r['total']); ?></td>
                <td><?php echo $r['status']; ?></td>
                <td>
                    <?php if($r['status']=='New'): ?>
                    <form method="POST" style="display:inline">
                        <input type="hidden" name="oid" value="<?php echo $r['order_id']; ?>">
                        <button name="approve_order" style="color:green">Duyệt</button>
                        <button name="cancel_order" style="color:red">Hủy</button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <div class="box" style="background:#fff4e6">
        <h2>3. Quản lý Hội viên</h2>
        <table>
            <tr><th>ID</th><th>Họ tên</th><th>Email</th><th>Vai trò</th><th>Thao tác</th></tr>
            <?php 
            $res = $conn->query("SELECT * FROM Member");
            while($r = $res->fetch_assoc()): ?>
            <tr>
                <td><?php echo $r['member_id']; ?></td>
                <td><?php echo $r['name']; ?></td>
                <td><?php echo $r['email']; ?></td>
                <td>
                    <?php echo ($r['role']=='admin') ? '<b style="color:red">ADMIN</b>' : 'Member'; ?>
                </td>
                <td>
                    <?php if($r['role'] != 'admin'): // Không cho phép xóa Admin ?>
                    <form method="POST" onsubmit="return confirm('CẢNH BÁO: Xóa hội viên này sẽ xóa hết đơn hàng của họ. Tiếp tục?');">
                        <input type="hidden" name="mid" value="<?php echo $r['member_id']; ?>">
                        <button name="delete_member" style="color:red">Xóa/Ban</button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

</body>
</html>