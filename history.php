<?php include 'db.php'; 
// SỬA LỖI: Kiểm tra user_id
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$uid = $_SESSION['user_id'];

// UC 13: HỦY ĐƠN HÀNG
if(isset($_POST['cancel_my_order'])) {
    $oid = $_POST['order_id'];
    // Hủy đơn 'New' của chính user đó
    $conn->query("UPDATE `Order` SET status='Cancelled' WHERE order_id=$oid AND user_id=$uid AND status='New'");
    echo "<script>alert('Đã hủy đơn hàng!'); window.location='history.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lịch sử đơn hàng</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <a href="index.php" class="logo">GYMFACE <span style="color:white">STORE</span></a>
        <a href="index.php">← Quay lại trang chủ</a>
    </nav>

    <div class="container">
        <h2>Lịch sử giao dịch của bạn</h2>

        <table border="1" style="width:100%; border-collapse:collapse; margin-top:20px">
            <tr style="background:#eee">
                <th>Mã đơn</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Chi tiết gói tập</th>
                <th>Thao tác</th>
            </tr>
            <?php
            // UC 12: Xem danh sách
            $sql = "SELECT * FROM `Order` WHERE user_id = $uid ORDER BY order_id DESC";
            $res = $conn->query($sql);
            
            if($res && $res->num_rows > 0) {
                while($row = $res->fetch_assoc()){
            ?>
                <tr>
                    <td>#<?php echo $row['order_id']; ?></td>
                    <td><?php echo date("d/m/Y H:i", strtotime($row['order_date'])); ?></td>
                    <td><b style="color:red"><?php echo number_format($row['total']); ?> đ</b></td>
                    <td>
                        <?php 
                            if($row['status']=='New') echo "<span style='color:blue; font-weight:bold'>Mới đặt</span>";
                            elseif($row['status']=='Paid') echo "<span style='color:green; font-weight:bold'>Đã thanh toán</span>";
                            else echo "<span style='color:gray; text-decoration:line-through'>Đã hủy</span>";
                        ?>
                    </td>
                    <td>
                        <ul style="margin:0; padding-left:20px">
                        <?php
                            $oid = $row['order_id'];
                            $details = $conn->query("SELECT d.*, s.name FROM Order_detail d JOIN Service_package s ON d.service_package_id = s.service_package_id WHERE order_id=$oid");
                            while($d = $details->fetch_assoc()){
                                echo "<li>{$d['name']} (x{$d['quantity']})</li>";
                            }
                        ?>
                        </ul>
                    </td>
                    <td>
                        <?php if($row['status'] == 'New'): ?>
                            <form method="POST" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn này?');">
                                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                <button type="submit" name="cancel_my_order" style="background:red; padding:5px 10px; font-size:12px">Hủy đơn</button>
                            </form>
                        <?php else: ?>
                            <span style="color:#999; font-size:12px">--</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center; padding:20px'>Bạn chưa có đơn hàng nào.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>