<?php
include 'db.php';
if(!isset($_SESSION['uid'])) die("Phải đăng nhập mới dùng được Giỏ hàng!");

$uid = $_SESSION['uid'];

// 1. THÊM VÀO GIỎ (Insert vào bảng Cart)
if(isset($_POST['add_db'])){
    $pkg_id = $_POST['pkg_id'];
    
    // Kiểm tra xem món này có trong bảng Cart của user này chưa
    $check = $conn->query("SELECT * FROM Cart WHERE member_id=$uid AND service_package_id=$pkg_id");
    
    if($check->num_rows > 0){
        // Có rồi thì tăng số lượng
        $conn->query("UPDATE Cart SET quantity = quantity + 1 WHERE member_id=$uid AND service_package_id=$pkg_id");
    } else {
        // Chưa có thì thêm mới
        $conn->query("INSERT INTO Cart (member_id, service_package_id, quantity) VALUES ($uid, $pkg_id, 1)");
    }
    header("Location: cart.php");
}

// 2. ĐẶT HÀNG (Chuyển từ Cart -> Order & Order_detail)
if(isset($_POST['checkout'])){
    $total = $_POST['total'];
    // Lấy đại ward_id = 1, shipping_id = 1 (Demo, thực tế phải cho chọn)
    $ward = 1; 
    $ship = 1; 
    
    // a. Tạo Order
    $sql_order = "INSERT INTO `Order` (member_id, shipping_id, ward_id, total, status) 
                  VALUES ($uid, $ship, $ward, $total, 'New')";
    $conn->query($sql_order);
    $order_id = $conn->insert_id; // Lấy ID đơn hàng vừa tạo
    
    // b. Chuyển từng món trong Cart sang Order_detail
    $cart_items = $conn->query("SELECT c.*, s.price FROM Cart c JOIN Service_package s ON c.service_package_id = s.service_package_id WHERE member_id=$uid");
    
    while($item = $cart_items->fetch_assoc()){
        $pid = $item['service_package_id'];
        $qty = $item['quantity'];
        $price = $item['price'];
        
        $conn->query("INSERT INTO Order_detail (order_id, service_package_id, quantity, price) 
                      VALUES ($order_id, $pid, $qty, $price)");
    }
    
    // c. Xóa Cart
    $conn->query("DELETE FROM Cart WHERE member_id=$uid");
    
    echo "<h2>Đặt hàng thành công! Mã đơn: #$order_id</h2> <a href='index.php'>Về trang chủ</a>";
    exit;
}
?>

<h2>Giỏ hàng của bạn (Lưu trong DB)</h2>
<table border="1" cellpadding="5">
    <tr><th>Tên gói</th><th>Giá</th><th>SL</th><th>Thành tiền</th></tr>
    <?php
    $sql = "SELECT c.*, s.name, s.price FROM Cart c 
            JOIN Service_package s ON c.service_package_id = s.service_package_id 
            WHERE member_id=$uid";
    $res = $conn->query($sql);
    $total = 0;
    
    while($row = $res->fetch_assoc()){
        $subtotal = $row['price'] * $row['quantity'];
        $total += $subtotal;
        echo "<tr>
            <td>{$row['name']}</td>
            <td>".number_format($row['price'])."</td>
            <td>{$row['quantity']}</td>
            <td>".number_format($subtotal)."</td>
        </tr>";
    }
    ?>
</table>
<h3>Tổng: <?php echo number_format($total); ?> đ</h3>

<form method="POST">
    <input type="hidden" name="total" value="<?php echo $total; ?>">
    <button name="checkout">XÁC NHẬN THANH TOÁN</button>
</form>