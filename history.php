<?php include 'db.php'; 
if(!isset($_SESSION['uid'])) { header("Location: login.php"); exit; }
$uid = $_SESSION['uid'];

// UC 13: HỦY ĐƠN HÀNG (Khách tự hủy)
if(isset($_POST['cancel_my_order'])) {
    $oid = $_POST['order_id'];
    // Chỉ được hủy đơn 'New'
    $conn->query("UPDATE `Order` SET status='Cancelled' WHERE order_id=$oid AND member_id=$uid AND status='New'");
    echo "<script>alert('Đã hủy đơn hàng!'); window.location='history.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head><title>Lịch sử đơn hàng</title></head>
<body>
    <a href="index.php"> <-- Quay lại trang chủ</a>
    <h2>Lịch sử giao dịch của bạn</h2>

    <table border="1" cellpadding="10">
        <tr>
            <th>Mã đơn</th>
            <th>Ngày đặt</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Chi tiết</th>
            <th>Thao tác</th>
        </tr>
        <?php
        // UC 12: Xem danh sách
        $sql = "SELECT * FROM `Order` WHERE member_id = $uid ORDER BY order_id DESC";
        $res = $conn->query($sql);
        
        if($res->num_rows > 0) {
            while($row = $res->fetch_assoc()){
        ?>
            <tr>
                <td>#<?php echo $row['order_id']; ?></td>
                <td><?php echo $row['order_date']; ?></td>
                <td><?php echo number_format($row['total']); ?> đ</td>
                <td>
                    <?php 
                        if($row['status']=='New') echo "<span style='color:blue'>Mới đặt</span>";
                        elseif($row['status']=='Paid') echo "<span style='color:green'>Đã thanh toán</span>";
                        else echo "<span style='color:red'>Đã hủy</span>";
                    ?>
                </td>
                <td>
                    <ul>
                    <?php
                        $oid = $row['order_id'];
                        $details = $conn->query("SELECT d.*, s.name FROM Order_detail d JOIN Service_package s ON d.service_package_id = s.service_package_id WHERE order_id=$oid");
                        while($d = $details->fetch_assoc()){
                            echo "<li>{$d['name']} x {$d['quantity']}</li>";
                        }
                    ?>
                    </ul>
                </td>
                <td>
                    <?php if($row['status'] == 'New'): ?>
                        <form method="POST" onsubmit="return confirm('Bạn chắc chắn muốn hủy?');">
                            <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                            <button type="submit" name="cancel_my_order">Hủy đơn</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php 
            }
        } else {
            echo "<tr><td colspan='6'>Bạn chưa có đơn hàng nào.</td></tr>";
        }
        ?>
    </table>
</body>
</html>