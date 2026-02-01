<?php
include 'db.php';

// 1. KIỂM TRA ĐĂNG NHẬP
if(!isset($_SESSION['user_id'])) {
    echo "<script>alert('Vui lòng đăng nhập để mua hàng!'); window.location='login.php';</script>";
    exit;
}

$uid = $_SESSION['user_id'];

// --- 2. XỬ LÝ THÊM VÀO GIỎ (ĐOẠN NÀY LÚC NÃY BỊ THIẾU) ---
if(isset($_POST['add_db'])){
    $pkg_id = $_POST['pkg_id'];
    
    // Kiểm tra xem đã có món này trong giỏ chưa
    $check = $conn->query("SELECT * FROM Cart WHERE user_id=$uid AND service_package_id=$pkg_id");
    
    if($check->num_rows > 0){
        // Có rồi thì tăng số lượng lên 1
        $conn->query("UPDATE Cart SET quantity = quantity + 1 WHERE user_id=$uid AND service_package_id=$pkg_id");
    } else {
        // Chưa có thì thêm mới
        $conn->query("INSERT INTO Cart (user_id, service_package_id, quantity) VALUES ($uid, $pkg_id, 1)");
    }
    // Load lại trang để thấy hàng trong giỏ
    header("Location: cart.php");
    exit;
}

// --- 3. XỬ LÝ XÓA SẢN PHẨM ---
if(isset($_POST['delete_item'])){
    $del_id = $_POST['del_id'];
    $conn->query("DELETE FROM Cart WHERE user_id=$uid AND service_package_id=$del_id");
    header("Location: cart.php");
    exit;
}

// --- 4. XỬ LÝ THANH TOÁN (CHECKOUT) ---
if(isset($_POST['checkout'])){
    $subtotal = $_POST['subtotal_hidden'];
    $ship_id = $_POST['shipping_method'];
    
    // Lấy giá ship
    $ship_row = $conn->query("SELECT cost FROM Shipping WHERE shipping_id=$ship_id")->fetch_assoc();
    $ship_cost = $ship_row['cost'];
    
    $final_total = $subtotal + $ship_cost;
    $ward = 1; // Mặc định

    // Tạo Order
    $sql_order = "INSERT INTO `Order` (user_id, shipping_id, ward_id, total, status) 
                  VALUES ($uid, $ship_id, $ward, $final_total, 'New')";
    
    if($conn->query($sql_order)) {
        $order_id = $conn->insert_id;
        
        // Chuyển hàng từ Cart sang Order Detail
        $cart_items = $conn->query("SELECT c.*, s.price FROM Cart c JOIN Service_package s ON c.service_package_id = s.service_package_id WHERE user_id=$uid");
        
        while($item = $cart_items->fetch_assoc()){
            $pid = $item['service_package_id'];
            $qty = $item['quantity'];
            $price = $item['price'];
            $conn->query("INSERT INTO Order_detail (order_id, service_package_id, quantity, price) 
                          VALUES ($order_id, $pid, $qty, $price)");
        }
        
        // Xóa sạch giỏ hàng
        $conn->query("DELETE FROM Cart WHERE user_id=$uid");
        echo "<script>alert('Đặt hàng thành công! Tổng tiền: ".number_format($final_total)."đ'); window.location='history.php';</script>";
    } else {
        echo "Lỗi: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Giỏ hàng & Thanh toán</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function updateTotal() {
            var subtotal = parseFloat(document.getElementById('subtotal_val').value); 
            var shipSelect = document.getElementById('ship_select');
            var shipCost = parseFloat(shipSelect.options[shipSelect.selectedIndex].getAttribute('data-price')); 
            var total = subtotal + shipCost;
            
            document.getElementById('display_ship').innerText = shipCost.toLocaleString('en-US') + ' đ';
            document.getElementById('display_total').innerText = total.toLocaleString('en-US') + ' đ';
        }
    </script>
</head>
<body>
    <nav>
        <a href="index.php" class="logo">GYMFACE <span style="color:white">CART</span></a>
        <a href="index.php">← Tiếp tục mua sắm</a>
    </nav>
    
    <div class="container">
        <h2>Giỏ hàng của bạn</h2>
        
        <table border="1" style="width:100%; border-collapse:collapse; margin-top:20px; background:white">
            <tr style="background:#eee">
                <th>Sản phẩm</th>
                <th>Đơn giá</th>
                <th>SL</th>
                <th>Thành tiền</th>
                <th>Xóa</th>
            </tr>
            <?php
            $sql = "SELECT c.*, s.name, s.price FROM Cart c 
                    JOIN Service_package s ON c.service_package_id = s.service_package_id 
                    WHERE user_id=$uid";
            $res = $conn->query($sql);
            $subtotal = 0; 
            
            if($res->num_rows > 0):
                while($row = $res->fetch_assoc()){
                    $line_total = $row['price'] * $row['quantity'];
                    $subtotal += $line_total;
            ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo number_format($row['price']); ?></td>
                    <td style="text-align:center"><?php echo $row['quantity']; ?></td>
                    <td><?php echo number_format($line_total); ?></td>
                    <td style="text-align:center">
                        <form method="POST" style="margin:0" onsubmit="return confirm('Xóa nhé?');">
                            <input type="hidden" name="del_id" value="<?php echo $row['service_package_id']; ?>">
                            <button name="delete_item" class="btn-delete">X</button>
                        </form>
                    </td>
                </tr>
            <?php
                }
            else:
                echo "<tr><td colspan='5' style='text-align:center; padding:20px'>Giỏ hàng trống</td></tr>";
            endif;
            ?>
        </table>

        <?php if($subtotal > 0): ?>
        
        <form method="POST" style="margin-top:30px; border-top:2px solid #333; padding-top:20px">
            <div style="display:flex; justify-content:space-between">
                <div style="width:45%">
                    <h3>Chọn phương thức vận chuyển:</h3>
                    <select name="shipping_method" id="ship_select" onchange="updateTotal()" style="width:100%; padding:10px; font-size:16px">
                        <?php
                        $ships = $conn->query("SELECT * FROM Shipping");
                        while($s = $ships->fetch_assoc()){
                            echo "<option value='{$s['shipping_id']}' data-price='{$s['cost']}'>
                                    {$s['type']} - ".number_format($s['cost'])." đ
                                  </option>";
                        }
                        ?>
                    </select>
                </div>

                <div style="width:45%; text-align:right">
                    <p>Tiền hàng: <b><?php echo number_format($subtotal); ?> đ</b></p>
                    <p>Phí vận chuyển: <b id="display_ship" style="color:blue">...</b></p>
                    <hr>
                    <h2 style="color:red">TỔNG CỘNG: <span id="display_total">...</span></h2>
                    <input type="hidden" name="subtotal_hidden" id="subtotal_val" value="<?php echo $subtotal; ?>">
                    <button name="checkout" style="width:100%; padding:15px; font-size:18px; background:#f39c12; color:white; border:none; font-weight:bold; cursor:pointer">
                        XÁC NHẬN ĐẶT HÀNG
                    </button>
                </div>
            </div>
        </form>
        <script>updateTotal();</script>
        <?php endif; ?>
    </div>
</body>
</html>